<template>
    <section id="form" class="mx-auto max-w-4xl px-6 py-18">
        <div class="mb-12 text-center">
            <p class="mb-2 text-sm uppercase tracking-[0.3em] text-cyan-400">
                Форма
            </p>

            <h3 class="text-2xl font-bold sm:text-3xl md:text-4xl">
                Свяжитесь со мной
            </h3>
        </div>

        <form
            @submit.prevent="submitForm"
            class="space-y-6 rounded-3xl border border-white/10 bg-white/5 p-8
            relative text-sm sm:text-base">
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

            <AiBox
                v-if="showAiBox"
                @close="showAiBox = false"
                @error="showError($event)"
                @success="showSuccess($event)"
                @generated="form.message = $event"
            />
            
            <div class="flex gap-4 flex-row">
                <button
                    :disabled="loading"
                    class="w-full rounded-2xl bg-cyan-500 py-4 font-semibold cursor-pointer
                    text-black transition hover:bg-cyan-400 disabled:opacity-50 sm:py-3 duration-300"
                >
                    {{ loading ? 'Отправка...' : 'Отправить' }}
                </button>

                <button
                    type="button"
                    @click.stop="showAiBox = !showAiBox"
                    class="rounded-2xl border border-cyan-400 px-6 py-3 text-cyan-400 duration-300
                    transition hover:bg-[#0f172a] cursor-pointer whitespace-nowrap sm:py-3"
                >
                    ✨ AI
                </button>
            </div>  
        </form>

        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform translate-y-2 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform translate-y-2 opacity-0"
        >
            <div
                v-if="successMessage"
                class="fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-2xl
                border border-green-500/20 bg-green-500/10 p-4 text-green-400 shadow-lg backdrop-blur-sm"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-sm font-medium">{{ successMessage }}</span>
            </div>
        </Transition>

        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform translate-y-2 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform translate-y-2 opacity-0"
        >
            <div
                v-if="errorMessage"
                class="fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-2xl
                border border-red-500/20 bg-red-500/10 p-4 text-red-400 shadow-lg backdrop-blur-sm"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span class="text-sm font-medium">{{ errorMessage }}</span>
            </div>
        </Transition>

    </section>
</template>

<script setup>
import axios from 'axios'
import { reactive, ref } from 'vue'
import AiBox from '../../components/AiBox.vue'

const showAiBox = ref(false)

const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const errorTimeout = ref(null)
const successTimeout = ref(null)

const form = reactive({
    name: '',
    email: '',
    phone: '',
    message: '',
})

const submitForm = async () => {

    errorMessage.value = ''

    if(form.phone && !validatePhone(form.phone)){
        showError('Введите корректный номер телефона!')
        return
    }else if(form.email && !validateEmail(form.email)){
        showError('Введите корректный email!')
        return
    }

    loading.value = true
 
    try{

        const res = await axios.post('/api/contact', form)

        showSuccess(res.data.message)

        form.name = ''
        form.email = ''
        form.phone = ''
        form.message = ''

    }catch(err){

        if(err.response?.status === 422){
            
            showError('Заполните все поля!')
        }else{

            showError('Ошибка отправки формы!')
        }
    }finally{

        loading.value = false
    }
}

const showError = (message) => {

    errorMessage.value = message

    if(errorTimeout.value){
        clearTimeout(errorTimeout.value)
    }

    errorTimeout.value = setTimeout(() => {
        errorMessage.value = ''
    }, 2000)
}

const showSuccess = (message) => {

    successMessage.value = message

    if(successTimeout.value){
        clearTimeout(successTimeout.value)
    }

    successTimeout.value = setTimeout(() => {
        successMessage.value = ''
    }, 2000)
}

const validatePhone = (phone) => {
    const cleaned = phone.replace(/[\s\-\(\)\+]/g, '')
    const phoneRegex = /^(\+7|7|8|9)?(\d{10})$/
    
    if(!cleaned || cleaned.length < 10 || cleaned.length > 11){
        return false
    }
    
    return phoneRegex.test(cleaned)
}

const validateEmail = (email) => {
    const emailRegex = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/
    return emailRegex.test(email)
}
</script>