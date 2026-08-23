<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" action="{{route('franchise.india-post-speed-post.excel_import.store')}}" enctype="multipart/form-data">
    @csrf
    <input type="file" name="excel_file" accept=".csv">
    <input type="submit" name="import" value="Import">
    </form>
    <table>
    <tr>
    <th>roll no</th>
    <th>name</th>
    <th>email</th>
    <th>mobile</th>
    </tr>

 @foreach($data as $row)
<tr>
<td><?=$row['roll_no']?></td>
<td><?=$row['name']?></td>
<td><?=$row['email']?></td>
<td><?=$row['mobile']?></td>

</tr>

@endforeach
    </table>
</body>
</html>