<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class TodoStorage
{
    private string $filePath = 'data/todos.json';

    public function all(): array
    {
        if (!Storage::exists($this->filePath)) {
            return [];
        }

        $content = Storage::get($this->filePath);
        $todos = json_decode($content, true);

        return is_array($todos) ? $todos : [];
    }

    public function find(string $id): ?array
    {
        $todos = $this->all();

        foreach ($todos as $todo) {
            if ($todo['id'] === $id) {
                return $todo;
            }
        }

        return null;
    }

    public function create(array $data): array
    {
        $todos = $this->all();

        $todo = [
            'id' => 'todo_' . uniqid() . time(),
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'completed' => false,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $todos[] = $todo;
        $this->save($todos);

        return $todo;
    }

    public function update(string $id, array $data): ?array
    {
        $todos = $this->all();
        $updated = false;

        foreach ($todos as $index => $todo) {
            if ($todo['id'] === $id) {
                $todos[$index]['title'] = $data['title'] ?? $todo['title'];
                $todos[$index]['description'] = $data['description'] ?? $todo['description'];
                $todos[$index]['completed'] = $data['completed'] ?? $todo['completed'];
                $todos[$index]['updated_at'] = date('Y-m-d H:i:s');
                $updated = true;
                break;
            }
        }

        if ($updated) {
            $this->save($todos);
            return $this->find($id);
        }

        return null;
    }

    public function delete(string $id): bool
    {
        $todos = $this->all();
        $originalCount = count($todos);

        $todos = array_filter($todos, function($todo) use ($id) {
            return $todo['id'] !== $id;
        });

        if (count($todos) < $originalCount) {
            $this->save(array_values($todos));
            return true;
        }

        return false;
    }

    public function stats(): array
    {
        $todos = $this->all();
        $total = count($todos);
        $completed = count(array_filter($todos, fn($todo) => $todo['completed']));
        $pending = $total - $completed;

        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending,
            'percentage' => $total > 0 ? round(($completed / $total) * 100) : 0
        ];
    }

    private function save(array $todos): void
    {
        Storage::put($this->filePath, json_encode($todos, JSON_PRETTY_PRINT));
    }
}
