<template>
    <div class="company-component">
      <h2>Companies</h2>
      <form class="my-10" @submit.prevent="createCompany">
        <input class="me-4" v-model="newCompany.name" placeholder="Company Name" required />
        <input class="me-4" v-model="newCompany.pib" placeholder="Company PIB" required />
        <input class="me-4" v-model="newCompany.address" placeholder="Company Address" required />
        <button class="bg-[#007bff] rounded-xl px-4 py-2.5 shadow-lg text-white text-sm font-bold" type="submit">Create Company</button>
      </form>

      <div class="min-w-full overflow-hidden overflow-x-auto align-middle sm:rounded-md">
            <table class="min-w-full border divide-y divide-gray-200">
                <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50">
                        <span
                            class="text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase">Name</span>
                    </th>
                    <th class="px-6 py-3 bg-gray-50">
                        <span
                            class="text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase">PIB</span>
                    </th>
                    <th class="px-6 py-3 bg-gray-50">
                        <span
                            class="text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase">Address</span>
                    </th>
                    <th class="px-6 py-3 bg-gray-50">
                        <span
                            class="text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase">Created By</span>
                    </th>
                </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                <template v-for="company in companies" :key="company.id">
                    <tr class="bg-white">
                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                            <a :href="`/companies/${company.id}`">
                                {{ company.name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                            {{ company.pib }}
                        </td>
                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                            {{ company.address }}
                        </td>
                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                            {{ company.admin.name }}
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        newCompany: {
          name: '',
          pib: '',
          address: ''
        },
        companies: []
      };
    },
    methods: {
      fetchCompanies() {
        axios.get('/api/companies').then(response => {
          this.companies = response.data;
        }).catch(error => {
          console.error("There was an error fetching the companies:", error);
        });
      },
      createCompany() {
        axios.post('/companies', this.newCompany).then(response => {
          this.companies.push(response.data);
          this.newCompany.name = '';
          this.newCompany.pib = '';
          this.newCompany.address = '';
        }).catch(error => {
          console.error("There was an error creating the company:", error);
        });
      }
    },
    mounted() {
      this.fetchCompanies();
    }
  };
  </script>

  <style scoped>
  .company-component {
    margin: 20px;
  }
  </style>
