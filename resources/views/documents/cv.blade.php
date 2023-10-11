<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CV</title>

    <style>
        * {
            margin: 0;
            background-color: #f5f5f5;

        }

        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            color: #333;
        }

        h2 {
            font-weight: bold;
            padding: 10px 0;
            /*border-bottom: 1px solid #333;*/
        }

        .h2 {

            border-top: 1px solid #333;
            margin-top: 1rem;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            padding-right: 12rem !important;
            background-color: #f5f5f5;
            min-height: 95%;
        }

        .heading {
            margin-bottom: 30px;
        }

        .summary {
            margin-bottom: 10px;
            padding-top: 3px !important;
            margin-top: 1rem;
            border-top: 1px solid #333;

        }

        .summary-p {
            width: 94%;
            margin-bottom: 0.3rem !important;
        }

        .education h4, .work h3, .skills h4 {
            margin-bottom: 5px;
        }

        .education p, .work p {
            margin: 0;
        }

        .education p:not(:last-child), .work p:not(:last-child) {
            margin-bottom: 10px;
        }

        .skills {
            display: flex;
        }

        .formation-titre {
            font-size: 1rem;
            font-weight: bolder;
        }

        .formation-date, .experience-date {
            margin-bottom: 0.6rem !important;
            /*margin-left: 0.4rem;*/
        }

        .skills h4 {
            margin-bottom: 15px;
        }

        .coor {
            margin-left: 1rem;
        }

        .name {
            font-size: 1.8rem;
            margin-left: 1rem;
        }

        .interet {
            font-size: 0.9rem;
            font-weight: bold;
            margin-left: 3px;
        }
    </style>
</head>

<body>
<div class="container">
    @isset($fullName)
        <section class="heading">
            <h2 class="name">{{ ucfirst($fullName) }}</h2>

            <p class="coor">Email : {{ $email }}</p>
            <p class="coor">Téléphone : {{ $tel }}</p>
            <p class="coor">Âge : {{ $age }}</p>
            @if (!empty($cv->propos) )
                <h2 class="h2">Résumé</h2>
                <p class="summary-p">{{ $cv->propos }}</p>
            @endif
        </section>

        @if (count($experiences) > 0)
            <section class="work">
                <h2 class="h2">Expérience professionnelle</h2>
                @foreach ($experiences as $experience)
                    <h3>{{ $experience->titre }}</h3>
                    <p class="experience-date">{{ $experience->dateDeb }} à {{ $experience->dateFin }}</p>
                @endforeach
            </section>
        @endif

        @if (count($formations) > 0)
            <section class="education">
                <h2 class="h2">Formation</h2>
                @foreach ($formations as $formation)
                    <span class="formation-titre">{{ $formation->titre }}</span>
                    <span class="formation-ins">{{ $formation->institut }}</span>
                    <p class="formation-date">{{ $formation->dateDeb }} à {{ $formation->dateFin }}</p>
                @endforeach
            </section>
        @endif

        @if (count($competences) > 0)
            <section class="skills">
                <h2 class="h2">Compétences</h2>
                @foreach ($competences as $competence)
                    <span class="formation-titre">{{ $competence->categorie }}</span>
                    <p class="formation-date">{{ $competence->desc }}</p>
                @endforeach
            </section>
        @endif

        @if (count($interets) > 0)
            <section class="interet">
                <h2 class="h2">Intérêts</h2>
                @foreach ($interets as $index => $interet)
                    <span class="interet">
                        {{ $interet->libelle }}
                        @if ($index < count($interets) - 1)
                            <span>,</span>
                        @endif
                    </span>
                @endforeach
            </section>
        @endif

    @endisset
</div>
</body>

</html>
