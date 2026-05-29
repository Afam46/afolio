<template>
    <section class="px-6 py-24">

        <div
            class="mx-auto max-w-5xl overflow-hidden rounded-3xl
            border border-white/10 bg-[#020617] shadow-2xl shadow-cyan-500/10"
        >

            <div
                class="flex items-center justify-between border-b
                border-white/10 bg-[#0f172a] px-6 py-4"
            >

                <div class="flex items-center gap-2">
                    <div class="h-3 w-3 rounded-full bg-red-500"/>
                    <div class="h-3 w-3 rounded-full bg-yellow-500"/>
                    <div class="h-3 w-3 rounded-full bg-green-500"/>
                </div>

                <p class="text-sm text-gray-400">
                    afam-terminal
                </p>

            </div>

            <div class="space-y-6 p-8 font-mono text-sm">

                <div>
                    <span class="text-cyan-400">
                        afam@portfolio:~$
                    </span>

                    <span class="ml-2 text-white">
                        init ai-assistant
                    </span>
                </div>

                <div
                    class="rounded-2xl border border-cyan-400/20
                    bg-cyan-400/5 p-5 text-gray-300"
                >   
                    <span class="text-green-400">afam@ai:~$</span>
                    <span class="text-cyan-400"> AI assistant initialized...</span>
                    <br>
                    <span class="text-green-400">afam@ai:~$</span>
                    <span class="text-cyan-400"> echo "" && cat ai_info.txt && echo ""</span>
                    <br>
                    <br>
                    <span>
                        Этот AI знает информацию о разработчике: направление - стек - навыки
                        - проекты - личные качества
                    </span>
                    <br>
                    <br>
                    <span class="text-green-400">afam@ai:~$ </span>
                    <span class="animate-pulse text-cyan-400">
                        {{terminalHistory.length > 2 ? "clear" : question }}
                    </span>
                </div>

                <div
                    class="rounded-2xl border border-white/10
                    bg-white/5 p-5 text-gray-300"
                >   
                    <div
                        v-for="element in terminalHistory.slice(0, -1)"
                        :key="element.command"
                    >
                        <span class="text-green-400">afam@ai:~$</span>
                        <span class="text-cyan-400">{{  element.command  }}</span>
                        <br>
                        <span>{{  element.output  }}</span>
                    </div>

                    <span class="text-green-400">afam@ai:~$</span>
                    <span class="text-cyan-400">{{  displayedCommand  }}</span>
                    <span class="animate-pulse text-cyan-400" v-if="showSpanCommand">|</span>

                    <br>

                    {{ displayedAnswer }}
                    <span class="animate-pulse text-cyan-400" v-if="showSpanDisplay">|</span>
                </div>

                <form
                    @submit.prevent="askAi"
                    class="flex flex-col gap-4 md:flex-row"
                >

                    <input
                        v-model="question"
                        type="text"
                        placeholder="Спросите что-нибудь..."
                        class="flex-1 rounded-2xl border border-white/10
                        bg-[#0f172a] px-5 py-4 text-white outline-none
                        transition focus:border-cyan-400"
                    >

                    <button
                        :disabled="loading"
                        class="rounded-2xl bg-cyan-500 px-8 py-4
                        font-semibold text-black transition
                        hover:bg-cyan-400 disabled:opacity-50"
                    >
                        {{ loading ? 'Thinking...' : 'Run' }}
                    </button>

                </form>

            </div>

        </div>

    </section>
</template>

<script setup>

import axios from 'axios'
import { ref } from 'vue'

const question = ref('')
const answer = ref('')
const loading = ref(false)
const displayedAnswer = ref('')
const displayedCommand = ref('')
const showSpanCommand = ref(false)
const showSpanDisplay = ref(false)
const terminalHistory = ref([])

const askAi = async () => {

    if(!question.value.trim()) return

    loading.value = true

    if(question.value == 'clear'){

        terminalHistory.value = []
        displayedAnswer.value = ''
        displayedCommand.value = ''
        question.value = ''
        loading.value = false
        return
    }

    try{

        const response = await axios.post('/api/ai-terminal', {
            question: question.value
        })

        const fullText = response.data.message

        const commandText = fullText.match(/COMMAND:(.*)/)?.[1] || ''
        const output = fullText.match(/OUTPUT:(.*)/s)?.[1] || ''

        displayedAnswer.value = ''
        displayedCommand.value = ''

        terminalHistory.value.push({
            command: commandText,
            output: output
        })

        showSpanCommand.value = true
        question.value = ''

        let commandIndex = 0

        const commandInterval = setInterval(() => {

            displayedCommand.value += commandText[commandIndex]

            commandIndex++

            if(commandIndex >= commandText.length){

                clearInterval(commandInterval)

                showSpanCommand.value = false

                let outputIndex = 0

                const outputInterval = setInterval(() => {

                    showSpanDisplay.value = true

                    displayedAnswer.value += output[outputIndex]

                    outputIndex++

                    if(outputIndex >= output.length){

                        clearInterval(outputInterval)

                        showSpanDisplay.value = false
                    }

                }, 15)

            }

        }, 30)

    }catch(err){

        answer.value = 'Ошибка AI сервиса.'

    }finally{

        loading.value = false
    }
}

</script>