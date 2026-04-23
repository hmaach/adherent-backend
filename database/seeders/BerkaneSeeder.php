<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Adherent;
use App\Models\Announce;
use App\Models\Secteur;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class BerkaneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add Admin and Test Adherent
        User::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@admin.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $testUser = User::create([
            'nom' => 'Adherent',
            'prenom' => 'Test',
            'email' => 'adherent@adherent.com',
            'role' => 'adherent',
            'password' => Hash::make('password'),
        ]);

        // Create a generic Secteur if it doesn't exist
        $secteurGen = Secteur::firstOrCreate(['lib' => 'Services Généraux']);

        Adherent::create([
            'user_id' => $testUser->id,
            'secteur_id' => $secteurGen->id,
            'propos' => 'Je suis un adhérent de test.',
            'profession' => 'Testeur',
            'ville' => 'Berkane',
            'img_path' => null,
        ]);

        $berkaneUsers = [
            [
                'prenom' => 'Ahmed', 'nom' => 'Alami', 'email' => 'ahmed@berkane.ma', 'profession' => 'Plombier',
                'profile_img' => 'https://images.unsplash.com/photo-1540569014015-19a7be504e3a?w=400',
                'announce_titre' => 'Installation et Réparation Plomberie', 'prix' => 50.00,
                'announce_img' => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=800',
                'desc' => 'Je propose des services professionnels de plomberie pour vos maisons et bureaux à Berkane. Intervention rapide pour fuites et nouvelles installations.'
            ],
            [
                'prenom' => 'Youssef', 'nom' => 'Benali', 'email' => 'youssef@berkane.ma', 'profession' => 'Électricien',
                'profile_img' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=400',
                'announce_titre' => 'Dépannage Électrique Rapide', 'prix' => 40.00,
                'announce_img' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=800',
                'desc' => 'Électricien qualifié pour tous vos problèmes électriques. Installation de compteurs, réparation de court-circuits, câblage complet.'
            ],
            [
                'prenom' => 'Karim', 'nom' => 'Ziani', 'email' => 'karim@berkane.ma', 'profession' => 'Mécanicien Agricole',
                'profile_img' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=400',
                'announce_titre' => 'Réparation de Matériel Agricole', 'prix' => 100.00,
                'announce_img' => 'https://images.unsplash.com/photo-1592982537447-6f23f5c18152?w=800',
                'desc' => 'Spécialiste dans la réparation de tracteurs, moissonneuses et systèmes d\'irrigation pour les fermes de la région de Berkane.'
            ],
            [
                'prenom' => 'Fatima', 'nom' => 'Zahra', 'email' => 'fatima@berkane.ma', 'profession' => 'Professeur',
                'profile_img' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400',
                'announce_titre' => 'Cours de Soutien à Domicile', 'prix' => 20.00,
                'announce_img' => 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=800',
                'desc' => 'Je donne des cours de soutien en Mathématiques et Physique-Chimie pour les élèves du collège et lycée. Pédagogie adaptée et résultats garantis.'
            ],
            [
                'prenom' => 'Mehdi', 'nom' => 'Chraibi', 'email' => 'mehdi@berkane.ma', 'profession' => 'Développeur Web',
                'profile_img' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=400',
                'announce_titre' => 'Création de Site Web Vitrine et E-commerce', 'prix' => 300.00,
                'announce_img' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800',
                'desc' => 'Je crée des sites web modernes, rapides et responsive pour les entreprises et commerces de Berkane souhaitant se digitaliser.'
            ],
            [
                'prenom' => 'Omar', 'nom' => 'Tazi', 'email' => 'omar@berkane.ma', 'profession' => 'Menuisier',
                'profile_img' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400',
                'announce_titre' => 'Création de Meubles sur Mesure', 'prix' => 150.00,
                'announce_img' => 'https://images.unsplash.com/photo-1540104539509-7c40d99dc074?w=800',
                'desc' => 'Artisan menuisier avec 10 ans d\'expérience. Création de placards, tables, et portes en bois massif avec des finitions parfaites.'
            ],
            [
                'prenom' => 'Hassan', 'nom' => 'Idrissi', 'email' => 'hassan@berkane.ma', 'profession' => 'Technicien Froid',
                'profile_img' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400',
                'announce_titre' => 'Installation et Entretien Climatiseur', 'prix' => 60.00,
                'announce_img' => 'https://images.unsplash.com/photo-1581092335397-9583eb92d232?w=800',
                'desc' => 'Préparez-vous pour l\'été ! Service d\'installation, nettoyage et recharge en gaz pour tous types de climatiseurs.'
            ],
            [
                'prenom' => 'Nadia', 'nom' => 'Mansour', 'email' => 'nadia@berkane.ma', 'profession' => 'Décoratrice',
                'profile_img' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400',
                'announce_titre' => 'Conseil en Décoration d\'Intérieur', 'prix' => 120.00,
                'announce_img' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=800',
                'desc' => 'Je vous accompagne dans le choix des couleurs, meubles et l\'aménagement de votre espace pour créer un intérieur qui vous ressemble.'
            ],
            [
                'prenom' => 'Salma', 'nom' => 'El Oufi', 'email' => 'salma@berkane.ma', 'profession' => 'Photographe',
                'profile_img' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400',
                'announce_titre' => 'Photographie pour Mariages et Événements', 'prix' => 200.00,
                'announce_img' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800',
                'desc' => 'Immortalisez vos meilleurs moments avec des photos de haute qualité. Disponible pour mariages, baptêmes et événements professionnels à Berkane.'
            ],
            [
                'prenom' => 'Tarik', 'nom' => 'Berrada', 'email' => 'tarik@berkane.ma', 'profession' => 'Chauffeur',
                'profile_img' => 'https://images.unsplash.com/photo-1600486913747-55e5470d6f40?w=400',
                'announce_titre' => 'Transport et Livraison de Marchandises', 'prix' => 80.00,
                'announce_img' => 'https://images.unsplash.com/photo-1617347454431-f49cd72f5b51?w=800',
                'desc' => 'Je dispose d\'un véhicule utilitaire pour assurer le transport de vos marchandises ou petits déménagements dans la région de l\'Oriental.'
            ]
        ];

        // Map to exact sector names used in SecteurSeeder
        $professionToSecteur = [
            'Plombier' => 'Plomberie & Chauffage',
            'Électricien' => 'Électricité & Éclairage',
            'Mécanicien Agricole' => 'Agriculture & Machinerie Agricole',
            'Professeur' => 'Soutien Scolaire & Enseignement',
            'Développeur Web' => 'Informatique & Développement Web',
            'Menuisier' => 'Menuiserie & Charpente',
            'Technicien Froid' => 'Climatisation & Froid',
            'Décoratrice' => 'Design & Décoration',
            'Photographe' => 'Photographie & Événementiel',
            'Chauffeur' => 'Transport & Logistique'
        ];

        foreach ($berkaneUsers as $index => $u) {
            $user = User::create([
                'nom' => $u['nom'],
                'prenom' => $u['prenom'],
                'email' => $u['email'],
                'role' => 'adherent',
                'password' => Hash::make('password'),
            ]);

            $secteurName = $professionToSecteur[$u['profession']] ?? 'Services Généraux';
            $secteurId = Secteur::where('lib', $secteurName)->first()->id ?? $secteurGen->id;

            $adherent = Adherent::create([
                'user_id' => $user->id,
                'secteur_id' => $secteurId,
                'propos' => $u['desc'],
                'profession' => $u['profession'],
                'ville' => 'Berkane',
                'img_path' => $u['profile_img'],
                'subscription_end_date' => Carbon::now()->addDays(rand(-10, 30)),
                'subscription_status' => rand(0, 10) > 2 ? 'active' : 'expired',
            ]);

            Announce::create([
                'user_id' => $user->id,
                'titre' => $u['announce_titre'],
                'prix' => $u['prix'],
                'desc' => $u['desc'],
                'img' => $u['announce_img'],
                'approved' => 1,
                'order' => $index + 1,
                'debut' => Carbon::now()->subDays(rand(1, 10)),
                'fin' => Carbon::now()->addDays(rand(10, 30)),
            ]);
        }
    }
}
