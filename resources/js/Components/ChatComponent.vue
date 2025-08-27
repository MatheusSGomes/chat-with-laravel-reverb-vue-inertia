<template>
    <div>
        <div class="flex flex-col justify-end h-80">
            <div ref="messagesContainer" class="p-4 overflow-y-auto max-h-fit">
                <div v-for="message in messages" :key="message.id" class="flex items-center mb-2">
                    <div v-if="message.sender_id == currentUser.id" class="p-2 ml-auto text-white bg-blue-500 rounded-lg">
                        {{ message.text }}
                    </div>

                    <div v-else class="p-2 mr-auto bg-gray-200 text-black rounded-lg">
                        {{ message.text }}
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center px-4 py-2">
            <input
                type="text"
                v-model="newMessage"
                @keyup.enter="sendMessage"
                @keydown="sendTypingEvent"
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

        <div class="flex items-center px-4">
            <small v-if="isFriendTyping" class="flex items-center text-gray-300 animate-pulse">
                {{ friend.name }} is typing
                <span class="flex ml-1 space-x-1">
                    <span class="w-1 h-1 bg-gray-700 rounded-full animate-bounce"></span>
                    <span class="w-1 h-1 bg-gray-700 rounded-full animate-bounce [animation-delay:0.2s]"></span>
                    <span class="w-1 h-1 bg-gray-700 rounded-full animate-bounce [animation-delay:0.4s]"></span>
                </span>
            </small>
        </div>
    </div>
</template>

<script setup>
import {nextTick, onMounted, ref, watch} from "vue"
import axios from "axios";

const props = defineProps({
    friend: {
        type: Object,
        required: true,
    },
    currentUser: {
        type: Object,
        required: true,
    },
});

const messages = ref([]);
const newMessage = ref("");
const messagesContainer = ref(null);
const isFriendTyping = ref(false);
const isFriendTypingTimer = ref(null);

watch(messages, () => {
    nextTick(() => {
        messagesContainer.value.scrollTo({
            top: messagesContainer.value.scrollHeight,
            behavior: "smooth"
        });
    });
}, { deep: true });

function sendMessage() {
  if (newMessage.value.trim() !== "") {
      axios.post(`/messages/${props.friend.id}`, {
          message: newMessage.value,
      })
      .then((response) => {
          messages.value.push(response.data);
          newMessage.value = "";
      })
  }
}

const sendTypingEvent = () => {
    Echo.private(`chat.${props.friend.id}`)
        .whisper("typing", {
            userID: props.currentUser.id,
        });
}

onMounted(() => {
    axios.get(`/messages/${props.friend.id}`)
        .then((response) => {
            messages.value = response.data;
        });

    Echo.private(`chat.${props.currentUser.id}`)
        .listen("MessageSent", (response) => {
            messages.value.push(response.message);
        })
        .listenForWhisper("typing", (response) => {
            isFriendTyping.value = response.userID === props.friend.id;

            if (isFriendTypingTimer.value) {
                clearTimeout(isFriendTypingTimer.value);
            }

            isFriendTypingTimer.value = setTimeout(() => {
                isFriendTyping.value = false;
            }, 1000);
        });
});
</script>
