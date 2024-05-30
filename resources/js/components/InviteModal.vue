<template>
    <button class="nav-link" @click="showModal">
        Invite User
    </button>

    <fwb-modal v-if="isShowModal" @close="closeModal">
      <template #header>
        <div class="flex items-center text-lg">
            Invite User
        </div>
      </template>
      <template #body>
        <fwb-input
            v-model="name"
            placeholder="enter name"
            label="First name"
            class="mb-3"
        />
        <fwb-input
            v-model="email"
            placeholder="enter email"
            label="Email"
        />
      </template>
      <template #footer>
        <div class="flex justify-between">
          <fwb-button @click="closeModal" color="alternative">
            Cancel
          </fwb-button>
          <fwb-button @click="inviteUser" color="green">
            Invite
          </fwb-button>
        </div>
      </template>
    </fwb-modal>
  </template>

  <script lang="ts" setup>
  import { ref } from 'vue'
  import { FwbButton, FwbModal, FwbInput } from 'flowbite-vue'
  import axios from 'axios'

  const isShowModal = ref(false)
  const name = ref('')
  const email = ref('')

  function closeModal () {
    isShowModal.value = false
  }

  async function inviteUser () {
    try {
        const response = await axios.post('/api/invite', {
        name: name.value,
        email: email.value
        })
        console.log('Invite sent successfully:', response.data)
        closeModal()
    } catch (error) {
        console.error('Error sending invite:', error)
        // Handle error (show error message to the user, etc.)
    }
    }

  function showModal () {
    isShowModal.value = true
  }
  </script>
