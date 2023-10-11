<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<fieldset style="display: inline-block;width: 20%;margin: auto">
    <legend>Poster un poste</legend>
    <form action="{{route('poster')}}" method="post">
        @csrf
        <select name="audience">
            <option value="tous">tous</option>
            <option value="filiere">filiere</option>
            <option value="groupe">groupe</option>
        </select><br>
        <input type="number" name="id_user" placeholder="id_user"><br>
        <input type="text" name="libelle" placeholder="libelle"><br>
        <select name="type">
            <option value="announce">announce</option>
            <option value="cour">cour</option>
            <option value="exercice">exercice</option>
            <option value="note">note</option>
        </select><br>
        <input type="submit" value="poster">
    </form>
</fieldset>
<div style="display: inline;width: 20%">
    <h3>Rechercher</h3>
    <form action="{{route('rechercher')}} " method="get">
        <input type="text" name="query" placeholder="rechercher ...">
        <input type="submit" value="Rechercher">
    </form>
</div>
<div style="display: inline;width: 20%">

    <h3>Notifications</h3>
    <table border="1">
        @foreach($notifications as $notification)
            <tr>
                <td>{{$notification->id}}</td>
                <td>{{$notification->dateNotif}}</td>
                <td>{{$notification->id_poste}}</td>
                <td>{{$notification->id_evenement}}</td>
            </tr>
        @endforeach
    </table>
</div>
<div style="display: inline;width: 20%">

    <h3>Stagiaires</h3>
    <table border="1">
        @foreach($stagiaires as $stagiaire)
            <tr>
                <td>{{$stagiaire->id}}</td>
                <td>{{$stagiaire->nom}}</td>
                <td>{{$stagiaire->prenom}}</td>
                <td>{{$stagiaire->id_groupe}}</td>
            </tr>
        @endforeach
    </table>
</div>
<div style="display: inline;width: 20%">

    <h3>Postes</h3>
    <table border="1">
        @foreach($postes as $poste)
            <tr>
                <td>{{$poste->id_user}}</td>
                <td>{{$poste->libelle}}</td>
                <td>{{$poste->type}}</td>
                <td>{{$poste->audience}}</td>
                <td>
                    <form action="{{route('reacter')}}" method="get">
                        <input type="hidden" value="4" name="user_id">
                        <input type="hidden" value="{{$poste->id}}" name="id_poste">
                        <input
                            type="submit"
                            value="React"
                        >
                    </form>
                </td>
                <td>
                    <form action="/update" method="get">
                        <input type="hidden" value="{{$poste->id}}" name="id">
                        <input
                            type="submit"
                            value="update"
                        >
                    </form>
                </td>
                <td>
                    <form action="/delete" method="get">
                        <input type="hidden" value="{{$poste->id}}" name="id">
                        <input
                            type="submit"
                            value="Supprimer"
                        >
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</div>

</body>
</html>
