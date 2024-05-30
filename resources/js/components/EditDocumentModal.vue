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
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-5" id="modal-title">Edit Document</h3>
                <form @submit.prevent="updateDocument">
                  <div class="mt-2">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input v-model="updatedDocument.title" type="text" id="title" class="mt-1 block w-full" required>
                  </div>
                  <div class="mt-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <input v-model="updatedDocument.description" type="text" id="description" class="mt-1 block w-full">
                  </div>
                  <div class="mt-4">
                    <label for="file" class="block text-sm font-medium text-gray-700">File</label>
                    <input type="file" @change="onFileChange" class="mt-2 mb-4 block w-full">
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
      document: {
        type: Object,
        required: true
      },
      canEdit: {
        type: Number,
        required: true
      },
    },
    data() {
      return {
        isOpen: false,
        updatedDocument: {
          title: this.document.title,
          description: this.document.description,
          file: null
        }
      };
    },
    methods: {
      openModal() {
        this.isOpen = true;
      },
      closeModal() {
        this.isOpen = false;
      },
      onFileChange(event) {
        this.updatedDocument.file = event.target.files[0];
      },
      updateDocument() {
        let formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('title', this.updatedDocument.title);
        formData.append('description', this.updatedDocument.description);
        if (this.updatedDocument.file) {
          formData.append('file', this.updatedDocument.file);
        }

        axios.post(`/api/documents/${this.document.id}`, formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(response => {
            console.log(response.data);
          this.$emit('document-updated', response.data);
          this.isOpen = false;
        }).catch(error => {
          console.error('There was an error updating the document!', error);
        });
      }
    }
  };
  </script>
