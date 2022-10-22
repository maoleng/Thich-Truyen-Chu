<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Chapter;
use App\Models\Comic;
use App\Models\Image;
use App\Models\Temp;
use App\Models\Type;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComicController extends Controller
{
    /**
     * @throws GuzzleException
     */
    public function getComics()
    {
        set_time_limit(6000);

        $client = new Client();


    }

    /**
     * @throws GuzzleException
     */
    public function clone()
    {
        set_time_limit(6000);

        $client = new Client();
        $comics = Temp::query()
            ->where('status', 0)
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->get();
        foreach($comics as $comic_data) {
            $content = $client->request('GET', $comic_data['link'])->getBody()->getContents();
            $comic_info = $this->getComicInfo($content);
            DB::beginTransaction();
            try {
                $comic = $this->createComic($comic_info, $comic_data);
                $this->createChapter($comic_info, $comic_data, $comic, $client);
                $comic_data->update([
                    'status' => 1,
                ]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                dd($e, $comic_data);
            }

        }

    }

    private function createChapter($comic_info, $comic_data, $comic, $client)
    {
        for ($i = 1; $i <= $comic_info['last_page']; $i++) {
            $page_url = $comic_data['link'].'trang-'.$i;
            $content = $client->request('GET', $page_url)->getBody()->getContents();
            $regex_link = '/'.str_replace('/', '\/', $comic_data['link']).'chuong-.*\//U';
            preg_match_all($regex_link, $content, $matches);
            $chaps = $matches[0];
            foreach ($chaps as $chap) {
                preg_match('/\/chuong.*\d/', $chap, $match);
                $chapter = substr($match[0], 8);
                $content = $client->request('GET', $chap)->getBody()->getContents();
                preg_match('/Chương <\/span><\/span>.+<\/a/Usu', $content, $match);
                $name = substr(str_replace('</span></span>', '', $match[0]), 0, -3);
                preg_match('/<div class=\"visible-md visible-lg ads-responsive incontent-ad\" id=\"ads-chapter-pc-top\" align=\"center\" style=\"height:90px\"><\/div>.*<\/div>/Us', $content, $matches);
                preg_match('/v>.*</s', $matches[0], $matches);
                $chap_content = substr(substr($matches[0], 2), 0, -1);
                $content_url = 'Comics/'.$comic->id.'/chap-'.$chapter.'.txt';
                Storage::disk('s3')->put($content_url, $chap_content);

                Chapter::query()->firstOrCreate(
                    [
                        'chap' => $chapter,
                        'comic_id' => $comic->id,
                    ],
                    [
                        'name' => $name,
                        'chap' => $chapter,
                        'content_url' => $content_url,
                        'comic_id' => $comic->id,
                    ]
                );
            }
        }
    }

    private function getComicsByPage($page, $client): array
    {
        $contents = $client->request('GET', Comic::NEW_COMIC_URL.'trang-'.$page)->getBody()->getContents();
        preg_match_all('/<h3 class="truyen-title" itemprop="name"><a href=".+"/U', $contents, $matches_link);
        preg_match_all('/data-desk-image=".+"/U', $contents, $matches_img);
        $matches = array_combine($matches_link[0], $matches_img[0]);
        $comics = [];
        foreach($matches as $key => $each) {
            preg_match('/https.+\//', $key, $match_link);
            preg_match('/https.+"/', $each, $match_img);
            $link = str_replace('/', '\/', $match_link[0]);
            $regex = '/<a href="'.$link.'.*chuong-.*\d\/"/U';
            preg_match($regex, $contents, $match);
            preg_match('/chuong-.*\d/', $match[0], $match);
            $count_chap = isset($match[0]) ? (int)substr($match[0], 7) : null;
            $comics[] = [
                'link' => $match_link[0],
                'img' => substr($match_img[0], 0, -1),
                'count_chap' => $count_chap,
            ];
        }

        return $comics;
    }

    private function createComic($comic_info, $comic_data)
    {
        $thumbnail = Image::query()->create([
            'source' => $this->handleImage($comic_data['banner'], 'thumbnail'),
            'size' => random_int(0,1000),
        ]);
        $banner = Image::query()->create([
            'source' => $this->handleImage($comic_info['banner_url'], 'banner'),
            'size' => random_int(0,1000),
        ]);
        $author = Author::query()->create([
            'name' => $comic_info['author'],
        ]);
        $type_ids = [];
        foreach($comic_info['type_names'] as $type_name) {
            $type = Type::query()->create([
                'name' => $type_name,
            ]);
            $type_ids[] = $type->id->toString();
        }

        $comic = Comic::query()->firstOrCreate(
            [
                'comic_id' => $comic_info['comic_id'],
            ],
            [
                'name' => $comic_info['comic_name'],
                'comic_id' => $comic_info['comic_id'],
                'thumbnail_id' => $thumbnail->id,
                'banner_id' => $banner->id,
                'description' => $comic_info['description'],
                'status' => $comic_info['status'],
                'count_chap' => $comic_data['count'],
                'author_id' => $author->id,
            ]
        );
        $comic->types()->sync($type_ids);

        return $comic;
    }

    private function getComicInfo($content)
    {
        preg_match('/<h3 class="title" itemprop="name">.+</Us', $content, $match);
        $comic_name = substr(substr($match[0],34), 0, -1);
        preg_match('/<div class="desc-text desc-text-full" itemprop="description">.+<\/di/sU', $content, $match);
        if (empty($match[0])) {
            $offset = 46;
            preg_match('/<div class="desc-text" itemprop="description">.+<\/di/sU', $content, $match);
        }
        $description = substr(substr($match[0], $offset ?? 61), 0, -4);
        preg_match('/<a itemprop="author".+</U', $content, $match);
        preg_match('/title=".+"/', $match[0], $match);
        $author = substr(substr($match[0], 7), 0, -1);
        preg_match('/h3>Thể loại:.+div>/Uu', $content, $match);
        preg_match_all('/title=".+"/U', $match[0], $matches);
        $type_names = array_map(static function ($type) {
            return substr(substr($type, 7), 0, -1);
        }, $matches[0]);
        preg_match('/text-[A-Za-z]+">.+</Us', $content, $match);
        $status = substr(substr($match[0], 14), 0, -1);
        $status = $status === 'Đang ra' ? 0 : 1;
        preg_match('/<div class="book"><img src=.+jpg/U', $content, $match);
        $banner_url = substr($match[0], 28);
        preg_match('/<input id="truyen-id" type="hidden" value="\d+">/', $content, $match);
        preg_match('/\d+/', $match[0], $match);
        $comic_id = $match[0];
        preg_match('/id="total-page".+>/U', $content, $match);
        preg_match('/\d+/', $match[0], $match);
        $last_page = $match[0];

        return [
            'comic_name' => $comic_name,
            'description' => $description,
            'author' => $author,
            'type_names' => $type_names,
            'status' => $status,
            'banner_url' => $banner_url,
            'comic_id' => $comic_id,
            'last_page' => $last_page,
        ];
    }

    public function handleImage($url, $type): array|string
    {
        if ($type === 'thumbnail') {
            $image_data = file_get_contents($url);
        } else {
            file_put_contents('example.jpg', file_get_contents($url));
            $im = imagecreatefromjpeg('example.jpg');
            $width = imagesx($im);
            $height = imagesy($im);
            $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => $width, 'height' =>  $height * 7 / 8]);
            File::delete('example.jpg');

            $text = env('APP_NAME');
            $tw = strlen($text) * imagefontwidth(15);

            $x = ($width - $tw) / 2;
            $y = $height - 60;
            $color = imagecolorallocate($im, 0, 0, 0);
            imagestring($im2, 15, $x, $y, $text, $color);
            ob_start();
            imagejpeg($im2);
            $image_data = ob_get_clean();
        }

        $name = 'Images/'.Str::random(10).'.jpg';
        Storage::disk('s3')->put($name, $image_data);

        return str_replace('%5C', '/', Storage::disk('s3')->url($name));
    }
}
