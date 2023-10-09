<div>
    <h1>List Component</h1>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <!-- 添加其他列 -->
        </tr>
        </thead>
        <tbody>
        @foreach($data as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <!-- 添加其他列 -->
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $data->links() }}
</div>
