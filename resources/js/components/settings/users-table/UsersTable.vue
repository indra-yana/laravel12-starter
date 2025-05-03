<script setup lang="ts">
import { columns } from '@/components/settings/users-table/columns';
import { onMounted, ref } from 'vue'
import { User } from '@/types';
import DataTable from '@/components/settings/users-table/DataTable.vue';

const data = ref<User[]>([])

// Fetch data from your API here.
async function getData(): Promise<User[]> {
  return [
  {
        id: 1,
        name: 'Ken99',
        email: 'ken99@yahoo.com',
        status: 'inactive',
    },
    {
        id: 2,
        name: 'Abe45',
        status: 'active',
        email: 'Abe45@gmail.com',
    },
    {
        id: 3,
        name: 'Monserrat44',
        status: 'active',
        email: 'Monserrat44@gmail.com',
    },
    {
        id: 4,
        name: 'Silas22',
        status: 'active',
        email: 'Silas22@gmail.com',
    },
    {
        id: 5,
        name: 'carmella',
        status: 'active',
        email: 'carmella@hotmail.com',
    },
  ]
}

onMounted(async () => {
  data.value = await getData()
})
</script>

<template>
  <div class="container py-10 mx-auto">
    <DataTable :columns="columns" :data="data" />
  </div>
</template>