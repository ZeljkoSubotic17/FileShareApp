<template>
    <div class="users-table">
      <h2 class="my-5 text-center">Users</h2>

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
                            class="text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase">Email</span>
                    </th>
                    <th class="px-6 py-3 bg-gray-50">
                        <span
                            class="text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase">Email Verified</span>
                    </th>
                    <th class="px-6 py-3 bg-gray-50">
                        <span
                            class="text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase">Admin</span>
                    </th>
                    <th class="px-6 py-3 bg-gray-50">
                        <span
                            class="text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase">Actions</span>
                    </th>
                </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                <template v-for="user in users" :key="user.id">
                    <tr class="bg-white">
                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                {{ user.name }}
                        </td>
                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                            {{ user.email }}
                        </td>
                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                            {{ user.email_verified_at }}
                        </td>
                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                            {{ user.is_admin }}
                        </td>
                        <td class="flex px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                            <edit-user-modal
                                :auth-user="authUser"
                                :can-edit="true"
                                :user="user"
                                @user-updated="handleUserUpdated"
                            ></edit-user-modal>
                            <a
                                @click="user.is_admin !== 1 ? deleteUser(user.id) : null"
                                class="font-medium text-red-600 dark:text-red-500 hower:underline mx-1"
                                :disabled="user.is_admin === 1"
                                :class="{'cursor-not-allowed opacity-50' : user.is_admin === 1}"
                            >
                                Delete
                            </a>
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
  import EditUserModal from './EditUserModal.vue';

  export default {
    props: {
        initialUsers: {
          type: Object,
          required: true,
        },
        authUser: {
          type: Object,
          required: true,
        },
    },
    data() {
      return {
        users: this.initialUsers,
      };
    },
    methods: {
        deleteUser(userId) {
            if (!window.confirm('Are you sure you want to delete this user?')) {
                return;
            }

            axios.delete(`/api/users/${userId}`)
            .then(response => {
                // Update the users array to remove the deleted user
                this.users  = this.users.filter(user => user.id !== userId);
            })
            .catch(error => {
                console.error('There was an error deleting the user!', error);
            });
        },
        handleUserUpdated(updatedUser) {
            // Find the index of the updated user in the users array and update it
            const index = this.users.findIndex(user => user.id === updatedUser.id);
            if (index !== -1) {
                this.users[index] = updatedUser;
            }
        },
    },
    mounted() {
        //
    }
  };
  </script>

  <style scoped>
  .users-table {
    margin: 20px;
  }
  </style>
