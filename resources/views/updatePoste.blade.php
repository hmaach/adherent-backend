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
    <form action="{{route('updatePoste')}}" method="post">
        @csrf
        <select name="audience">
            <option value="tous"
                {{ $poste->audience === 'tous' ? 'selected' : ""}}
            >
                tous
            </option>
            <option value="filiere"
                {{ $poste->audience === 'filiere' ? 'selected' : ""}}
            >
                filiere
            </option>
            <option value="groupe"
                {{ $poste->audience === 'groupe' ? 'selected' : ""}}
            >
                groupe
            </option>
        </select><br>
        <input type="hidden" name="id" value="{{$poste->id}}" >
        <input type="number" name="id_user" value="{{$poste->id_user}}" placeholder="id_user"><br>
        <input type="text" name="libelle" value="{{$poste->libelle}}" placeholder="libelle"><br>
        <select name="type">
            <option value="announce"
                {{ $poste->type === 'announce' ? 'selected' : ""}}
            >
                announce
            </option>
            <option value="cour"
                {{ $poste->type === 'cour' ? 'selected' : ""}}
            >
                cour
            </option>
            <option value="exercice"
                {{ $poste->type === 'exercice' ? 'selected' : ""}}
            >
                exercice
            </option>
            <option value="note"
                {{ $poste->type === 'note' ? 'selected' : ""}}
            >
                note
            </option>
        </select><br>
        <input type="submit" value="update">
    </form>
</fieldset>
</body>
</html>
