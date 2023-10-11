<!-- cv.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>CV</title>
    <style>
        /* CSS styles for the CV layout */
        /* Add your own styles here */
    </style>
</head>
<body>
<header>
    <h1>CV</h1>
</header>

{{--<section>--}}
{{--    <h2>Personal Information</h2>--}}
{{--    <p>Name: {{ $stagiaire->name }}</p>--}}
{{--    <p>Email: {{ $stagiaire->email }}</p>--}}
{{--    <!-- Add more personal information fields as needed -->--}}
{{--</section>--}}

<section>
    <h2>Interests</h2>
    @foreach($stagiaire->interets as $interet)
        <p>{{ $interet->libelle }}</p>
    @endforeach
</section>

{{--<section>--}}
{{--    <h2>Group</h2>--}}
{{--    <p>Group Name: {{ $stagiaire->groupe->name }}</p>--}}
{{--    <p>Filiere: {{ $stagiaire->groupe->filiere->name }}</p>--}}
{{--</section>--}}

{{--<section>--}}
{{--    <h2>Skills</h2>--}}
{{--    @foreach($stagiaire->competences as $competence)--}}
{{--        <p>{{ $competence->name }}</p>--}}
{{--    @endforeach--}}
{{--</section>--}}

{{--<section>--}}
{{--    <h2>Experiences</h2>--}}
{{--    @foreach($stagiaire->experiences as $experience)--}}
{{--        <p>{{ $experience->title }}</p>--}}
{{--        <p>{{ $experience->description }}</p>--}}
{{--        <!-- Add more experience fields as needed -->--}}
{{--    @endforeach--}}
{{--</section>--}}

{{--<section>--}}
{{--    <h2>Education</h2>--}}
{{--    @foreach($stagiaire->formations as $formation)--}}
{{--        <p>{{ $formation->degree }}</p>--}}
{{--        <p>{{ $formation->university }}</p>--}}
{{--        <!-- Add more education fields as needed -->--}}
{{--    @endforeach--}}
{{--</section>--}}
</body>
</html>
