<form method="POST" action="{{ route('import') }}" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">
    <button type="submit">Import</button>
</form>
