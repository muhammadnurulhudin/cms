<?php
namespace Udiko\Cms\Http\Controllers;

use \Udiko\Cms\Models\User;
use App\Http\Controllers\Controller;

class SetupController extends Controller
{

    public function index()
    {
        if (db_connected()) {
            $this->generate_dummy_content();
            regenerate_cache();
            recache_option();
            clear_route();


        }

    }
    function generate_dummy_content()
    {
        $data = array('username' => 'admin', 'password' => bcrypt('admin'), 'email' => 'admin@email.com', 'status' => 'Aktif', 'slug' => 'admin-web', 'name' => 'Admin Web', 'url' => 'author/admin-web', 'photo' => null, 'level' => 'admin');
        $id = User::UpdateOrcreate(['username' => 'admin'], $data);
        $a = 0;
        while ($a <= 50):
            $id->post()->updateOrcreate(
                [
                    'title' => $title = fake()->sentence,
                    'slug' => $slug = str()->slug($title),
                    'content' => fake()->paragraph,
                    'thumbnail' => 'thumbnail.png',
                    'url' => 'berita/' . $slug,
                    'status' => 'publish',
                    'type' => 'berita',
                ]
            );
            $a++;

        endwhile;
        //create menu
        $id->post()->updateOrcreate(
            [
                'title' => $title = 'Header',
                'slug' => $slug = str()->slug($title),
                'status' => 'publish',
                'type' => 'menu',
                'data_loop' => json_encode([['id' => 'm1', 'parent' => 0, 'parent' => 0, 'name' => 'Profil', 'description' => null, 'link' => '#', 'icon' => null], ['id' => 'm2', 'parent' => 'm1', 'parent' => 0, 'name' => 'Visi Misi', 'description' => null, 'link' => '#', 'icon' => null], ['id' => 'm3', 'parent' => 'm1', 'parent' => 0, 'name' => 'Sejarah', 'description' => null, 'link' => '#', 'icon' => null], ['id' => 'm4', 'parent' => 0, 'parent' => 0, 'name' => 'Publikasi', 'description' => null, 'link' => '#', 'icon' => null], ['id' => 'm5', 'parent' => 'm4', 'parent' => 0, 'name' => 'Berita', 'description' => null, 'link' => '#', 'icon' => null], ['id' => 'm6', 'parent' => 'm4', 'parent' => 0, 'name' => 'Agenda', 'description' => null, 'link' => '#', 'icon' => null]]),
            ]
        );

        $option = array(
            ['name' => 'site_maintenance', 'value' => 0, 'autoload' => 1],
            ['name' => 'post_perpage', 'value' => 10, 'autoload' => 1],
            ['name' => 'site_title', 'value' => 'Your Website Official'],
            ['name' => 'template', 'value' => 'newzona', 'autoload' => 1],
            ['name' => 'admin_path', 'value' => 'admin', 'autoload' => 1],
            ['name' => 'logo', 'value' => 'logo.png', 'autoload' => 1],
            ['name' => 'favicon', 'value' => 'favicon.png', 'autoload' => 1],
            ['name' => 'background', 'value' => 'background.png', 'autoload' => 1],
            ['name' => 'site_url', 'value' => 'fix.test', 'autoload' => 1],
            ['name' => 'site_keyword', 'value' => 'Web, Official, New', 'autoload' => 1],
            ['name' => 'site_description', 'value' => 'My Offical Web', 'autoload' => 1],
            ['name' => 'company_name', 'value' => 'My Offical Web', 'autoload' => 1],
            ['name' => 'address', 'value' => 'Anggrek Streen, 2', 'autoload' => 1],
            ['name' => 'phone', 'value' => '123456789', 'autoload' => 1],
            ['name' => 'email', 'value' => 'your@email.com', 'autoload' => 1],
            ['name' => 'fax', 'value' => '123456789', 'autoload' => 1],
            ['name' => 'latitude', 'value' => null, 'autoload' => 1],
            ['name' => 'longitude', 'value' => null, 'autoload' => 1],
            ['name' => 'facebook', 'value' => 'https://fb.com/yourcompany', 'autoload' => 1],
            ['name' => 'youtube', 'value' => 'https://youtube.com/@yourchannel', 'autoload' => 1],
            ['name' => 'instagram', 'value' => null, 'autoload' => 1],
            ['name' => 'google_verify', 'value' => null, 'autoload' => 1],
            ['name' => 'backup_password', 'value' => null, 'autoload' => 1],
            ['name' => 'api_allow_ip', 'value' => '127.0.0.1', 'autoload' => 1],
            ['name' => 'comment_status', 'value' => 0, 'autoload' => 1],
            ['name' => 'home_page', 'value' => 'default', 'autoload' => 1],
        );


        foreach ($option as $row) {

            \Udiko\Cms\Models\Option::updateOrCreate($row);
        }
        return true;
    }
}
