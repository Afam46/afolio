<template>
   <div
        ref="aiModal"
        class="absolute z-50 top-1/3 mt-4
        w-full max-w-sm md:w-96
        left-1/2 -translate-x-1/2
        md:left-auto md:right-0 md:translate-x-0
        rounded-2xl border border-cyan-400
        bg-[#0f172a] p-4
        shadow-lg shadow-black
        "
    >
        <div class="mb-4 flex items-center justify-between">
            <h4 class="text-base sm:text-lg font-semibold text-cyan-400">
                AI комментарий
            </h4>

            <button
                @click="emit('close')"
                class="text-gray-400 transition hover:text-white cursor-pointer
                text-xl"
            >
                ✕
            </button>
        </div>

        <input
            v-model="aiTopic"
            @keydown.enter.prevent="generateMessage"
            type="text"
            placeholder="Ключевые слова для комментария"
            class="w-full rounded-xl border border-white/10 bg-[#020617]
            px-4 py-3 outline-none transition focus:border-cyan-400"
        >

        <button
            @click="generateMessage"
            :disabled="aiLoading"
            type="button"
            class="mt-4 w-full rounded-xl bg-cyan-500 px-6 py-3
            font-semibold text-black transition hover:bg-cyan-400 duration-300
            disabled:opacity-50 cursor-pointer"
        >
            {{ aiLoading ? 'Генерация...' : 'Сгенерировать' }}
        </button>
    </div>
</template>

<script setup>
import axios from 'axios'
import { ref, onMounted, onBeforeUnmount, defineEmits } from 'vue'

const emit = defineEmits([
    'close',
    'error',
    'success',
    'generated'
])

const aiLoading = ref(false)
const aiModal = ref(null)
const aiTopic = ref('')

onMounted(() => {

    document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {

    document.removeEventListener('click', handleClickOutside)
})

const generateMessage = async () => {

    if(!aiTopic.value){

        emit('error', 'Введите тему для генерации!')
        return
    }

    aiLoading.value = true

    try{

        const res = await axios.post('/api/ai-message', {
            topic: aiTopic.value
        })

        emit('generated', res.data.message)
        emit('success', res.data.success)

        aiTopic.value = ''

        emit('close')

    }catch(err){
        emit('error', 'Ошибка AI генерации')
    }

    aiLoading.value = false
}

const handleClickOutside = (event) => {

    if(aiModal.value && !aiModal.value.contains(event.target)){

        emit('close')
    }
}

</script>