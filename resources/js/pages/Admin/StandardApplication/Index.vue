<template>
    <div>
        <h1>Standard Applications</h1>
        <Link :href="route('admin.standard-applications.create')" class="btn btn-primary mb-4">Add Standard Application
        </Link>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="application in applications" :key="application.id">
                    <td>{{ application.title }}</td>
                    <td>
                        <Link :href="route('admin.standard-applications.edit', application.id)"
                            class="btn btn-sm btn-secondary">Edit</Link>
                        <form :action="route('admin.standard-applications.destroy', application.id)" method="post"
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
        applications: Array,
    },
};
</script>
