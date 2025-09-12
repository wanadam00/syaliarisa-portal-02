<template>
    <div>
        <h1>Legislations</h1>
        <Link :href="route('admin.legislations.create')" class="btn btn-primary mb-4">Add Legislation</Link>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="legislation in legislations" :key="legislation.id">
                    <td>{{ legislation.title }}</td>
                    <td>{{ legislation.type }}</td>
                    <td>
                        <Link :href="route('admin.legislations.edit', legislation.id)" class="btn btn-sm btn-secondary">
                        Edit</Link>
                        <form :action="route('admin.legislations.destroy', legislation.id)" method="post"
                            class="d-inline">
                            <input type="hidden" name="_method" value="delete">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
export default {
    props: {
        legislations: Array,
    },
};
</script>
