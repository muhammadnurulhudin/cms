<?php
return [
    'menu' => [
        'berita' => [
            'position' => 1,
            'name' => 'berita',
            'title' => 'Berita',
            'description' => 'Menu Untuk Mengelola Berita',
            'parent' => false,
            'icon' => 'fa-newspaper-o',
            'data_title' => 'Judul Berita',
            'custom_column' => false,
            'post_parent' => false,
            'custom_field' => array(
                ['Reporter', 'text'],
                ['Tanggal Entry', 'text']
            ),
            'looping' => false,
            'looping_data' => false,
            'looping_for' => 'Di Isi Hanya Untuk Kecamatan',
            'thumbnail' => true,
            'editor' => true,
            'group' => true,
            'api' => true,
            'archive' => true,
            'index' => true,
            'detail' => true,
            'operator' => true,
            'public' => true,
            'history' => true,
            'auto_query' => true,
            'auto_load' => true,
            'crud' => ['create','read','update','delete'],
            'active' => true,

        ],
        'agenda' => [
            'position' => 2,
            'name' => 'agenda',
            'title' => 'Agenda',
            'description' => 'Menu Untuk Mengelola Agenda',
            'parent' => false,
            'icon' => 'fa-calendar',
            'data_title' => 'Nama Agenda',
            'custom_column' => false,
            'post_parent' => false,
            'custom_field' => array(
                ['Tanggal', 'date', 'required'],
                ['Tempat', 'text', 'required'],
                ['Alamat', 'text']
            ),
            'looping' => false,
            'looping_data' => false,
            'looping_for' => 'Silahkan Isi Lampiran',
            'thumbnail' => false,
            'editor' => false,
            'group' => false,
            'api' => false,
            'archive' => false,
            'index' => true,
            'detail' => true,
            'operator' => false,
            'public' => true,
            'history' => false,
            'auto_query' => false,
            'auto_load' => true,
            'crud' => ['create','read','update','delete'],
            'active' => true,

        ],
        'pengumuman' => [
            'position' => 3,
            'name' => 'pengumuman',
            'title' => 'Pengumuman',
            'description' => 'Menu Untuk Mengelola Pengumuman',
            'parent' => false,
            'icon' => 'fa-bullhorn',
            'data_title' => 'Judul Pengumuman',
            'custom_column' => false,
            'post_parent' => false,
            'custom_field' => false,
            'looping' => false,
            'looping_data' => array(
                ['Nama Lampiran', 'text'],
                ['File', 'file'],
            ),
            'looping_for' => 'Silahkan Isi Lampiran',
            'thumbnail' => false,
            'editor' => true,
            'group' => false,
            'api' => false,
            'archive' => false,
            'index' => true,
            'detail' => true,
            'operator' => false,
            'public' => true,
            'history' => false,
            'auto_query' => false,
            'auto_load' => true,
            'crud' => ['create','read','update','delete'],
            'active' => true,

        ],
        'media' => [
            'position' => 4,
            'name' => 'media',
            'title' => 'Media',
            'description' => 'Menu Untuk Melihat Media Yang Di Upload',
            'parent' => false,
            'icon' => 'fa-image',
            'data_title' => 'Nama Berkas',
            'custom_column' => 'Ukuran',
            'post_parent' => false,
            'custom_field' => [['Ukuran', 'text'], ['Width', 'text'], ['Height', 'text'], ['Extension', 'text']],
            'looping' => false,
            'looping_data' => false,
            'looping_for' => 'Silahkan Isi Berkas',
            'thumbnail' => false,
            'editor' => false,
            'group' => false,
            'api' => false,
            'archive' => false,
            'index' => false,
            'detail' => true,
            'operator' => true,
            'public' => true,
            'history' => false,
            'auto_query' => false,
            'auto_load' => false,
            'crud' => ['read','update'],
            'active' => true,

        ],
        'foto' => [
            'position' => 1,
            'name' => 'foto',
            'title' => 'Foto',
            'description' => 'Menu Untuk Mengelola Dokumentasi',
            'parent' => false,
            'icon' => 'fa-camera',
            'data_title' => 'Judul Foto',
            'custom_column' => false,
            'post_parent' => false,
            'custom_field' => false,
            'looping' => false,
            'looping_data' => array(
                ['Caption', 'text'],
                ['File', 'file'],
            ),
            'looping_for' => 'Masukkan Koleksi Foto',
            'thumbnail' => true,
            'editor' => true,
            'group' => false,
            'api' => false,
            'archive' => false,
            'index' => true,
            'detail' => true,
            'operator' => false,
            'public' => true,
            'history' => false,
            'auto_query' => false,
            'auto_load' => true,
            'crud' => ['create','read','update','delete'],
            'active' => true,

        ],
        'video' => [
            'position' => 2,
            'name' => 'video',
            'title' => 'Video',
            'description' => 'Menu Untuk Mengelola video',
            'parent' => false,
            'icon' => 'fa-youtube',
            'data_title' => 'Judul video',
            'custom_column' => false,
            'post_parent' => false,
            'custom_field' => false,
            'looping' => false,
            'looping_data' => array(
                ['Caption', 'text'],
                ['File', 'file'],
            ),
            'looping_for' => 'Masukkan Koleksi Foto',
            'thumbnail' => true,
            'editor' => true,
            'group' => false,
            'api' => false,
            'archive' => false,
            'index' => true,
            'detail' => true,
            'operator' => false,
            'public' => true,
            'history' => false,
            'auto_query' => false,
            'auto_load' => true,
            'crud' => ['create','read','update','delete'],
            'active' => true,

        ],
        'download' => [
            'position' => 4,
            'name' => 'download',
            'title' => 'Download',
            'description' => 'Menu Untuk Mengelola Unduhan',
            'parent' => false,
            'icon' => 'fa-download',
            'data_title' => 'Nama Berkas',
            'custom_column' => false,
            'post_parent' => false,
            'custom_field' => [['File', 'file']],
            'looping' => false,
            'looping_data' => false,
            'looping_for' => 'Silahkan Isi Berkas',
            'thumbnail' => true,
            'editor' => false,
            'group' => true,
            'api' => false,
            'archive' => false,
            'index' => true,
            'detail' => true,
            'operator' => false,
            'public' => true,
            'history' => false,
            'auto_query' => false,
            'auto_load' => true,
            'crud' => ['create','read','update','delete'],
            'active' => true,

        ],
        'halaman' => [
            'position' => 3,
            'name' => 'halaman',
            'title' => 'Halaman',
            'description' => 'Menu Untuk Mengelola Halaman Static',
            'parent' => false,
            'icon' => 'fa-align-center',
            'data_title' => 'Judul Halaman',
            'custom_column' => false,
            'post_parent' => false,
            'custom_field' => false,
            'looping' => 'Asset',
            'looping_data' => array(['Nama File', 'text'], ['File', 'file']),
            'looping_for' => 'Upload Asset',
            'thumbnail' => false,
            'editor' => true,
            'group' => false,
            'api' => false,
            'archive' => false,
            'index' => false,
            'detail' => true,
            'operator' => true,
            'public' => true,
            'history' => true,
            'auto_query' => false,
            'auto_load' => false,
            'crud' => ['create','read','update','delete'],
            'active' => true,
        ],
        'tautan' => [
            'position' => 102,
            'name' => 'tautan',
            'title' => 'Tautan',
            'description' => 'Menu Untuk Mengelola Tautan',
            'parent' => false,
            'icon' => 'fa-link',
            'data_title' => 'Nama Tautan',
            'custom_column' => false,
            'post_parent' => false,
            'custom_field' => false,
            'looping' => false,
            'looping_data' => false,
            'looping_for' => 'Di Isi Hanya Untuk Kecamatan',
            'thumbnail' => false,
            'editor' => false,
            'group' => false,
            'api' => false,
            'archive' => false,
            'index' => false,
            'detail' => true,
            'operator' => true,
            'public' => true,
            'history' => false,
            'auto_query' => false,
            'auto_load' => true,
            'crud' => ['create','read','update','delete'],
            'active' => true,

        ],
        'banner' => [
            'position' => 99,
            'name' => 'banner',
            'title' => 'Banner',
            'description' => 'Menu Untuk Mengelola Banner',
            'parent' => false,
            'icon' => 'fa-image',
            'data_title' => 'Nama Banner',
            'custom_column' => false,
            'post_parent' => false,
            'custom_field' => array(['Link', 'text']),
            'looping' => false,
            'looping_data' => false,
            'looping_for' => 'Silahkan Masukkan Nama Menu',
            'thumbnail' => true,
            'editor' => false,
            'group' => true,
            'api' => false,
            'archive' => false,
            'index' => false,
            'detail' => false,
            'operator' => false,
            'public' => false,
            'history' => false,
            'auto_query' => false,
            'auto_load' => false,
            'crud' => ['create','read','update','delete'],
            'active' => true,

        ],
        'domain' => [
            'position' => 100,
            'name' => 'domain',
            'title' => 'Domain',
            'description' => 'Menu Untuk Mengelola Domain',
            'parent' => false,
            'icon' => 'fa-android',
            'data_title' => 'Nama Domain',
            'custom_column' => 'Description',
            'post_parent' => false,
            'custom_field' => array(
                ['App Info', 'break'],
                ['App Name', 'text'],
                ['App URL', 'text'],
                ['Description', 'text'],
                ['Display', 'break'],
                ['Favicon', 'file'],
                ['Logo', 'file'],
                ['Background', 'file'],
                ['Database', 'break'],
                ['Host', 'text'],
                ['Username', 'text'],
                ['Password', 'text'],
                ['Database', 'text'],
            ),
            'looping' => 'Route',
            'looping_data' => array(
                ['Name', 'text'],
                ['Path', 'text'],
                ['Function', 'text'],
                ['Controller', 'text']
            ),
            'looping_for' => 'Silahkan Masukkan Nama Route',
            'thumbnail' => true,
            'editor' => false,
            'group' => false,
            'api' => false,
            'archive' => false,
            'index' => false,
            'detail' => false,
            'operator' => false,
            'public' => false,
            'history' => false,
            'auto_query' => false,
            'auto_load' => false,
            'crud' => ['create','read','update','delete'],
            'active' => true,

        ],
        'sambutan' => [
            'position' => 99,
            'name' => 'sambutan',
            'title' => 'Sambutan',
            'description' => 'Menu Untuk Mengelola Ucapan',
            'parent' => false,
            'icon' => 'fa-quote-left',
            'data_title' => 'Judul Sambutan',
            'custom_column' => false,
            'post_parent' => false,
            'custom_field' => array(['Nama Pemberi Sambutan', 'text'], ['Jabatan', 'text'], ['NIP', 'text']),
            'looping' => false,
            'looping_data' => false,
            'looping_for' => 'Silahkan Masukkan Nama Menu',
            'thumbnail' => true,
            'editor' => true,
            'group' => false,
            'api' => false,
            'archive' => false,
            'index' => false,
            'detail' => true,
            'operator' => true,
            'public' => false,
            'history' => false,
            'auto_query' => false,
            'auto_load' => true,
            'crud' => ['create','read','update','delete'],
            'active' => true,

        ],
        'menu' => [
            'position' => 99,
            'name' => 'menu',
            'title' => 'Menu',
            'description' => 'Menu Untuk Mengelola Menu Navigasi',
            'parent' => false,
            'icon' => 'fa-share-alt-square',
            'data_title' => 'Nama Menu',
            'custom_column' => false,
            'post_parent' => false,
            'custom_field' => false,
            'looping' => 'Menu',
            'looping_data' => array(
                ['ID', 'text'],
                ['PARENT', 'text'],
                ['NAME', 'text'],
                ['DESCRIPTION', 'text'],
                ['LINK', 'text'],
                ['ICON', 'text'],
            ),
            'looping_for' => 'Silahkan Atur Menu',
            'thumbnail' => false,
            'editor' => false,
            'group' => false,
            'api' => false,
            'archive' => false,
            'index' => false,
            'detail' => false,
            'operator' => false,
            'public' => false,
            'history' => false,
            'auto_query' => false,
            'auto_load' => true,
            'crud' => ['create','read','update','delete'],
            'active' => true,

        ],
        'kepegawaian' => [
            'position' => 19,
            'name' => 'kepegawaian',
            'title' => 'Kepegawaian',
            'description' => 'Menu Untuk Mengelola Kepegawaian',
            'parent' => false,
            'icon' => 'fa-address-card',
            'data_title' => 'Nama Pegawai',
            'custom_column' => 'NIP',
            'post_parent' => ['Unit Kerja', 'unit-kerja'],
            'custom_field' => [
                ['NIP', 'text', 'required'],
                ['Jabatan', 'text', 'required'],
                ['Pendidikan', 'text', 'required'],
            ],
            'looping' => false,
            'looping_data' => false,
            'looping_for' => 'Silahkan ',
            'thumbnail' => true,
            'editor' => false,
            'group' => true,
            'api' => false,
            'archive' => false,
            'index' => false,
            'detail' => true,
            'operator' => true,
            'public' => true,
            'history' => false,
            'auto_query' => false,
            'auto_load' => true,
            'crud' => ['create','read','update','delete'],
            'active' => true,

        ],
        'faq' => [
            'position' => 18,
            'name' => 'faq',
            'title' => 'FAQ',
            'description' => 'Menu Untuk Mengelola Jenis Pelayanan',
            'parent' => false,
            'icon' => 'fa-question-circle',
            'data_title' => 'Pertanyaan',
            'custom_column' => false,
            'post_parent' => false,
            'custom_field' => false,
            'looping' => false,
            'looping_data' => array(['Nama File', 'text'], ['File', 'file']),
            'looping_for' => 'Silahkan Masukkan Informasi Jabatan',
            'thumbnail' => false,
            'editor' => true,
            'group' => false,
            'api' => false,
            'archive' => false,
            'index' => false,
            'detail' => false,
            'operator' => true,
            'public' => true,
            'history' => false,
            'auto_query' => false,
            'auto_load' => true,
            'crud' => ['create','read','update','delete'],
            'active' => true,

        ],
        'unit-kerja' => [
            'position' => 19,
            'name' => 'unit-kerja',
            'title' => 'Unit Kerja',
            'description' => 'Menu Untuk Mengelola Unit Kerja',
            'parent' => false,
            'icon' => 'fa-desktop',
            'data_title' => 'Nama Unit Kerja',
            'custom_column' => false,
            'post_parent' => false,
            'custom_field' => false,
            'looping' => false,
            'looping_data' => array(
                ['Jabatan', 'text'],
                ['Nama Pejabat', 'text'],
                ['NIP', 'text'],
                ['Foto', 'file']
            ),
            'looping_for' => 'Silahkan Masukkan Informasi Jabatan',
            'thumbnail' => false,
            'editor' => true,
            'group' => false,
            'api' => false,
            'archive' => false,
            'index' => false,
            'detail' => true,
            'operator' => true,
            'public' => true,
            'history' => false,
            'auto_query' => false,
            'auto_load' => true,
            'crud' => ['create','read','update','delete'],
            'active' => true,
        ]
        ],
        'config'=> [
            'web_type'=> null,
            'optionable'=> null,
        ],
        'used'=> array(),
        'current'=> null,
        'data'=> null,
        'sid'=> session()->getId()
];
