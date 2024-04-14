<div style="height: 400px; overflow-y: auto;">
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Frags</th>
            <th>Time</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($players as $player)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $player['Name'] }}</td>
                <td>{{ $player['Frags'] }}</td>
                <td>{{ $player['TimeF'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
