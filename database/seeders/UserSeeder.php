<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@dnsc.edu.ph',
            'email_verified_at' => now(),
            'password' => '$2y$10$cSqURtekr48ONKZGezKPNe/eNezGSHvwNlYh87VsKgVPQYYAP4bay', // Dnsc1234
            'remember_token' => Str::random(10),
        ]);
        
        $user = User::create([
            'name' => 'Joena Marie M. Agod',
            'email' => 'agod.joenamarie@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Aivie Villarta Alfeche',
            'email' => 'alfeche.aivie@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Nezel Ann B. Bantayan',
            'email' => 'bantayan.nezelann@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Edison Quilisadio Bernaldez',
            'email' => 'bernaldez.edison@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Frincess Jade Cabuga Cajano',
            'email' => 'cajano.frincessjade@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Bonita Penaso Cantere',
            'email' => 'cantere.bonita@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user->offices()->attach([22 => ['isHead'=> 1]]);
        $user = User::create([
            'name' => 'Danvier C. Cruz',
            'email' => 'cruz.danvier@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Ronel Geraldizo Dagohoy',
            'email' => 'dagohoy.ronel@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Niño Joseph Tio Fabia',
            'email' => 'fabia.ninojoseph@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Joanaros Precillo Fahit',
            'email' => 'fahit.joanaros@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Florie Ann Lupida Fermil',
            'email' => 'fermil.florieann@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15 => ['isHead' => 1]]);
        $user = User::create([
            'name' => 'Gerry Louis Ordaneza Gallano',
            'email' => 'gallano.gerrylouis@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Charlo Bianci Montehermos Guray',
            'email' => 'guray.charlobianci@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user->offices()->attach([34 => ['isHead' => 1]]);
        $user = User::create([
            'name' => 'Arvin Bargayo Gutang',
            'email' => 'gutang.arvin@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Eliseo Fermo Huesca',
            'email' => 'huesca.eliseo@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user->offices()->attach([30 => ['isHead' => 1]]);
        $user = User::create([
            'name' => 'James M. Jadraque',
            'email' => 'jadraque.james@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Marilou Delfin Junsay',
            'email' => 'junsay.marilou@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Glenne Berja Lagura',
            'email' => 'lagura.glenne@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
        $user = User::create([
            'name' => 'Junry Remedios Lanoy',
            'email' => 'lanoy.junry@dnsc.edu.ph',
        ]);
        $user->offices()->attach([15]);
    }
}
