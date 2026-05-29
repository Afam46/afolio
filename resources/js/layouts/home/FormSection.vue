<template>
    <section id="form" class="mx-auto max-w-4xl px-6 py-24">
        <div class="mb-12 text-center">
            <p class="mb-2 text-sm uppercase tracking-[0.3em] text-cyan-400">
                Форма
            </p>

            <h3 class="text-4xl font-bold">
                Свяжитесь со мной
            </h3>
        </div>

        <form
            @submit.prevent="submitForm"
            class="space-y-6 rounded-3xl border border-white/10 bg-white/5 p-8 relative">
            <div class="grid gap-6 md:grid-cols-2">

                <input
                    v-model="form.name"
                    type="text"
                    placeholder="Имя"
                    class="rounded-2xl border border-white/10 bg-[#0f172a]
                    px-5 py-4 outline-none transition focus:border-cyan-400"
                >

                <input
                    v-model="form.email"
                    type="email"
                    placeholder="Email"
                    class="rounded-2xl border border-white/10 bg-[#0f172a]
                    px-5 py-4 outline-none transition focus:border-cyan-400"
                >
            </div>

            <input
                v-model="form.phone"
                type="text"
                placeholder="Телефон"
                class="w-full rounded-2xl border border-white/10 bg-[#0f172a]
                px-5 py-4 outline-none transition focus:border-cyan-400"
            >

            <textarea
                v-model="form.message"
                rows="5"
                placeholder="Комментарий"
                class="w-full rounded-2xl border border-white/10 bg-[#0f172a]
                px-5 py-4 outline-none transition focus:border-cyan-400"
            />

            <div
                v-if="showAiBox"
                ref="aiModal"
                class="absolute right-0 top-1/3 z-50 w-96 rounded-2xl border border-cyan-400
                bg-[#0f172a] p-4 shadow-lg shadow-black"
            >
                <div class="mb-4 flex items-center justify-between">
                    <h4 class="text-lg font-semibold text-cyan-400">
                        AI комментарий
                    </h4>

                    <button
                        @click="showAiBox = false"
                        class="text-gray-400 transition hover:text-white cursor-pointer
                        text-xl"
                    >
                        ✕
                    </button>
                </div>

                <input
                    v-model="aiTopic"
                    type="text"
                    placeholder="Ключевые слова для комментария"
                    class="w-full rounded-xl border border-white/10 bg-[#020617]
                    px-4 py-3 outline-none transition focus:border-cyan-400"
                >

                <button
                    @click="generateMessage"
                    @keydown.enter.prevent="generateMessage"
                    :disabled="aiLoading"
                    type="button"
                    class="mt-4 w-full rounded-xl bg-cyan-500 px-6 py-3
                    font-semibold text-black transition hover:bg-cyan-400
                    disabled:opacity-50 cursor-pointer"
                >
                    {{ aiLoading ? 'Генерация...' : 'Сгенерировать' }}
                </button>
            </div>

            <div class="flex gap-4">
                <button
                    :disabled="loading"
                    class="w-full rounded-2xl bg-cyan-500 py-4 font-semibold cursor-pointer
                    text-black transition hover:bg-cyan-400 disabled:opacity-50"
                >
                    {{ loading ? 'Отправка...' : 'Отправить' }}
                </button>

                <button
                    type="button"
                    @click.stop="showAiBox = !showAiBox"
                    class="rounded-2xl border border-cyan-400 px-6 py-3 text-cyan-400
                    transition hover:bg-[#0f172a] cursor-pointer whitespace-nowrap"
                >
                    ✨ AI
                </button>
            </div>
            <div class="mb-3 h-0.5 w-0.5">
                 <p
                    v-if="successMessage"
                    class="text-green-400 absolute"
                >
                    {{ successMessage }}
                </p>

                <p
                    v-if="errorMessage"
                    class="text-red-400 absolute"
                >
                    {{ errorMessage }}
                </p>
            </div>
           
        </form>
    </section>
</template>

<script setup>
import axios from 'axios'
import { reactive, ref, onMounted, onBeforeUnmount } from 'vue'

const aiModal = ref(null)
const showAiBox = ref(false)
const aiTopic = ref('')
const aiLoading = ref(false)

const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const form = reactive({
    name: '',
    email: '',
    phone: '',
    message: '',
})

onMounted(() => {

    document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {

    document.removeEventListener('click', handleClickOutside)
})

const submitForm = async () => {

    loading.value = true
    successMessage.value = ''
 

    try{

        const res = await axios.post('/api/contact', form)

        successMessage.value = res.data.message

        form.name = ''
        form.email = ''
        form.phone = ''
        form.message = ''
        errorMessage.value = ''

    }catch(err){

        if(err.response?.status === 422){

            errorMessage.value = 'Заполните все поля!'
        }else{

            errorMessage.value = 'Ошибка отправки формы!'
        }
    }finally{

        loading.value = false
    }
}

const generateMessage = async () => {

    if(!aiTopic.value){
        errorMessage.value = 'Введите тему для генерации!'
        return
    }

    aiLoading.value = true

    try{

        const res = await axios.post('/api/ai-message', {
            topic: aiTopic.value
        })

        form.message = res.data.message

        aiTopic.value = ''
        showAiBox.value = false

    }catch(err){

        errorMessage.value = 'Ошибка AI генерации'

    }finally{

        aiLoading.value = false
    }
}

const handleClickOutside = (event) => {

    if(
        aiModal.value &&
        !aiModal.value.contains(event.target)
    ){
        showAiBox.value = false
    }
}

</script>