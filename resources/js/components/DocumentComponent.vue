<template>
    <div class="document-component">

        <form @submit.prevent="uploadDocument" enctype="multipart/form-data" class="my-5 flex flex-wrap sm:flex-nowrap items-center space-y-3 sm:space-y-0 sm:space-x-3">
            <div class="w-full sm:w-auto flex-grow mb-3">
                <input class="w-full px-3 py-2 border rounded" v-model="newDocument.title" placeholder="Document Title" required />
            </div>
            <div class="w-full sm:w-auto flex-grow mb-3">
                <input class="w-full px-3 py-2 border rounded" v-model="newDocument.description" placeholder="Document Description" />
            </div>
            <div class="w-full sm:w-auto flex-grow mb-3">
                <input
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none
                    dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" aria-describedby="file_input_help" id="file_input" type="file"
                    @change="onFileChange" required ref="fileInput"
                />
            </div>
            <div class="w-full sm:w-auto mb-3">
                <button class="w-full bg-gray-900 rounded-xl px-4 py-2.5 shadow-lg text-white text-sm" type="submit">Upload Document</button>
            </div>
        </form>

        <h2 class="text-center mb-5">Documents</h2>

      <div class="min-w-full overflow-hidden overflow-x-auto align-middle sm:rounded-md">
        <table class="min-w-full border divide-y divide-gray-200">
          <thead>
            <tr>
                <th class="px-6 py-3 bg-gray-50">
                    <span
                        class="text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase">Title</span>
                </th>
                <th class="px-6 py-3 bg-gray-50">
                    <span
                        class="text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase">Description</span>
                </th>
                <th class="px-6 py-3 bg-gray-50">
                    <span
                        class="text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase">Created By</span>
                </th>
                <th class="px-6 py-3 bg-gray-50">
                    <span
                        class="text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase">Actions</span>
                </th>
            </tr>
          </thead>

          <tbody class="bg-white divide-y divide-gray-200 divide-solid">
            <template v-for="document in documents" :key="document.id">
              <tr class="bg-white">
                <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                    <a :href="`/storage/${document.file_path}`" target="_blank">
                        {{ document.title }}
                    </a>
                </td>
                <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                    {{ document.description }}
                </td>
                <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                    {{ document.user ? document.user.name : 'Unknown' }}
                </td>
                <td class="flex px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                    <edit-document-modal
                        :document="document"
                        @document-updated="updateDocumentInList"
                        :disabled="!canEditOrDelete(document)"
                        :class="{'cursor-not-allowed opacity-50': !canEditOrDelete(document)}"
                        :canEdit="canEditOrDelete(document)"
                    ></edit-document-modal>
                    <a
                        @click="canEditOrDelete(document) ? deleteDocument(document.id) : null"
                        class="font-medium text-red-600 dark:text-red-500 hower:underline mx-1"
                        :disabled="!canEditOrDelete(document)"
                        :class="{'cursor-not-allowed opacity-50': !canEditOrDelete(document)}"
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
import EditDocumentModal from './EditDocumentModal.vue';

  export default {
    components: {
        EditDocumentModal
    },
    data() {
      return {
        newDocument: {
          title: '',
          description: '',
          file: null
        },
        documents: []
      };
    },
    props: {
        user: {
        type: Object,
        required: true,
        },
    },
    methods: {
      canEditOrDelete(document) {
        return this.user.is_admin || document.user.id === this.user.id;
      },
      fetchDocuments() {
        axios.get('/api/documents').then(response => {
          this.documents = response.data;
        });
      },
      onFileChange(event) {
        this.newDocument.file = event.target.files[0];
      },
      uploadDocument() {
        let formData = new FormData();
        formData.append('title', this.newDocument.title);
        formData.append('description', this.newDocument.description);
        formData.append('file', this.newDocument.file);

        axios.post('/api/documents', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        }).then(response => {
          this.documents.push(response.data);
          this.newDocument.title = '';
          this.newDocument.description = '';
          this.newDocument.file = null;
          this.$refs.fileInput.value = '';
        });
      },
      deleteDocument(documentId) {
        if (!window.confirm('Are you sure you want to delete this document?')) {
            return;
        }
        axios.delete(`/api/documents/${documentId}`)
          .then(response => {
            // Update the documents array to remove the deleted document
            this.documents = this.documents.filter(document => document.id !== documentId);
          })
          .catch(error => {
            console.error('There was an error deleting the document!', error);
          });
      },
      updateDocumentInList(updatedDocument) {
      const index = this.documents.findIndex(doc => doc.id === updatedDocument.id);
      if (index !== -1) {
        this.documents.splice(index, 1, updatedDocument);
      }
    }
    },
    mounted() {
      this.fetchDocuments();
    }
  };
  </script>

  <style scoped>
  .document-component {
    margin: 20px;
  }
  </style>
