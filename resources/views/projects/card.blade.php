<div class="card" style="height: 200px;">
    <h3 class="text-xl font-normal py-4 -ml-5 border-l-4 border-blue-500 pl-4 mb-3">
        <a href="{{ route('projects.show', $project->id) }}" class="text-black no-underline">
            {{ $project->title }}
        </a>
    </h3>

    <div class="text-gray-500">{{ str_limit($project->description, 100) }}</div>

    <footer>
        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="text-right">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-red">Delete</button>
        </form>
    </footer>
</div>
