<template>
<div>
    <div class="flex flex-col justify-end h-80">
        <div class="p-4 overflow-y-auto max-h-fit">
            <div v-for="message in messages" :key="message.id" class="flex items-center mb-2">
                <div v-if="message.isMe" class="p-2 ml-auto text-white bg-blue-500 rounded-lg">
                    {{ message.text }}
                </div>

                <div v-else class="p-2 mr-auto bg-gray-200 text-black rounded-lg">
                    {{ message.text }}
                </div>
            </div>

            <div class="flex items-center p-4">
                <input
                    type="text"
                    v-model="newMessage"
                    @keyup.enter="sendMessage"
                    placeholder="Type a message..."
                    class="flex-1 px-2 py-1 border rounded-lg bg-transparent"
                    autofocus
                />
                <button
                    @click="sendMessage"
                    class="px-4 py-1 ml-2 text-white bg-blue-500 rounded-lg"
                >
                    Send
                </button>
            </div>
        </div>
    </div>
</div>
</template>

<script setup>
import { ref } from "vue"

const messages = ref([
  { id: 1, text: "Hello", isMe: false },
  { id: 2, text: "Hi there!", isMe: true },
])

const newMessage = ref("")

function sendMessage() {
  if (newMessage.value.trim() !== "") {
    messages.value.push({
      id: messages.value.length + 1,
      text: newMessage.value,
      isMe: true,
    })
    newMessage.value = ""
  }
}
</script>