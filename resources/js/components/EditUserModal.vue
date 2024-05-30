  <template>
    <div>
      <a v-on="canEdit ? { click: openModal } : {}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mx-1">Edit</a>

      <div v-if="isOpen" class="fixed z-10 inset-0 overflow-y-auto flex items-center justify-center">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full p-6">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-5" id="modal-title">Edit User</h3>
                <form @submit.prevent="updateUser">
                  <div class="mt-2">
                    <label for="title" class="block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="updatedUser.name" type="text" id="title" class="mt-1 block w-full" required>
                  </div>
                  <div class="mt-4 flex justify-end">
                    <button type="button" @click="closeModal" class="mr-2 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>
import axios from 'axios';

  export default {
    props: {
      authUser: {
        type: Object,
        required: true
      },
      canEdit: {
        type: Boolean,
        required: true
      },
      user: {
        type: Object,
        required: true
      },
    },
    data() {
      return {
        isOpen: false,
        updatedUser: {
          name: this.user.name,
        }
      };
    },
    watch: {
        user(newUser) {
        this.updatedUser.name = newUser.name;
        }
    },
    methods: {
      openModal() {
        this.isOpen = true;
      },
      closeModal() {
        this.isOpen = false;
      },
      updateUser() {
        let formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('name', this.updatedUser.name);

        axios.post(`/api/users/${this.user.id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(response => {
            console.log(response.data);
          this.$emit('user-updated', response.data);
          this.isOpen = false;
        }).catch(error => {
          console.error('There was an error updating the document!', error);
        });
      }
    }
  };
  </script>
