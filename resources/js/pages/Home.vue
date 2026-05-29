<template>
    <div class="min-h-screen bg-[#0f172a] text-white">
        <Header />

        <main>
            <HeroSection />
            <AiTerminal/>
            <AboutSection />
            <ProjectsSection />
            <ContactsSection />
            <FormSection />
        </main>
    </div>
</template>

<script setup>

import Header from '../layouts/home/Header.vue'
import HeroSection from '../layouts/home/HeroSection.vue'
import AboutSection from '../layouts/home/AboutSection.vue'
import ProjectsSection from '../layouts/home/ProjectsSection.vue'
import ContactsSection from '../layouts/home/ContactsSection.vue'
import FormSection from '../layouts/home/FormSection.vue'
import AiTerminal from '../layouts/home/AiTerminal.vue'

</script>