<script setup lang="ts">
import { computed, ref } from 'vue';

import ErrorMessage from '@/components/admin/Form/ErrorMessage.vue';
import ToggleInput from '@/components/admin/Form/ToggleInput.vue';
import IconBold from '@/components/admin/Icon/IconBold.vue';
import IconCode from '@/components/admin/Icon/IconCode.vue';
import IconItalic from '@/components/admin/Icon/IconItalic.vue';
import IconLink from '@/components/admin/Icon/IconLink.vue';
import IconList from '@/components/admin/Icon/IconList.vue';
import IconOrderedList from '@/components/admin/Icon/IconOrderedList.vue';
import IconQuote from '@/components/admin/Icon/IconQuote.vue';

import { renderMarkdown } from '../utils/text';

defineProps<{
    label: string;
    name: string;
    error?: string;
    required?: boolean;
}>();

const model = defineModel<string>({ default: '' });
const preview = ref(false);
const textareaRef = ref<HTMLTextAreaElement | null>(null);

const renderedMarkdown = computed(() => {
    if (!model.value) {
        return '';
    }

    return renderMarkdown(model.value);
});

const getSelectedText = () => {
    const textarea = textareaRef.value;

    if (!textarea) {
        return;
    }

    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;

    return model.value.substring(start, end);
};

const wrapSelection = (before: string, after?: string) => {
    const selectedText = getSelectedText();

    if (selectedText?.startsWith(before) && selectedText?.endsWith(after ?? '')) {
        // Remove the wrapping
        model.value = model.value.replace(selectedText, selectedText.substring(before.length, selectedText.length - (after?.length ?? 0)));

        return;
    }

    const wrappedText = `${before}${selectedText}${after ?? ''}`;

    // Deprecated but still works in most browsers and no alternative
    document.execCommand('insertText', false, wrappedText);
};
</script>

<template>
    <div class="flex flex-col">
        <label :for="name" class="text-md pb-1 pl-1.5 font-medium">{{ label }}</label>
        <div class="border-secondary-300 flex flex-wrap items-center rounded-t-md border-1 border-b-0 p-1.5">
            <Action tag="button" size="small" variant="icon" title="Bold" @click.prevent="wrapSelection('**', '**')">
                <span class="sr-only">Bold</span>
                <IconBold width="4" />
            </Action>
            <Action tag="button" size="small" variant="icon" title="Italic" @click.prevent="wrapSelection('*', '*')">
                <span class="sr-only">Italic</span>
                <IconItalic width="4" />
            </Action>
            <Action tag="button" size="small" variant="icon" title="Link" @click.prevent="wrapSelection('[', '](url)')">
                <span class="sr-only">Link</span>
                <IconLink width="4" />
            </Action>
            <Action tag="button" size="small" variant="icon" title="Code" @click.prevent="wrapSelection('`', '`')">
                <span class="sr-only">Code</span>
                <IconCode width="4" />
            </Action>
            <Action tag="button" size="small" variant="icon" title="Blockquote" @click.prevent="wrapSelection('> ')">
                <span class="sr-only">Blockquote</span>
                <IconQuote width="4" />
            </Action>
            <Action tag="button" size="small" variant="icon" title="Unordered List" @click.prevent="wrapSelection('- ')">
                <span class="sr-only">Unordered List</span>
                <IconList width="4" />
            </Action>
            <Action tag="button" size="small" variant="icon" title="Ordered List" @click.prevent="wrapSelection('1. ')">
                <span class="sr-only">Ordered List</span>
                <IconOrderedList width="4" />
            </Action>
            <ToggleInput class="ml-auto" label="Preview" name="preview" v-model="preview" reverse />
        </div>
        <div class="flex w-full flex-col">
            <textarea
                ref="textareaRef"
                v-show="!preview"
                class="border-secondary-300 h-60 w-full overflow-auto rounded-b-md border-1 px-3 py-2.5"
                :name="name"
                :id="name"
                v-model="model"
                :required="required"
            ></textarea>
            <div
                v-show="preview"
                class="wysiwyg border-secondary-300 inset-0 h-60 w-full overflow-auto rounded-b-md border-1 px-3 py-2.5"
                v-html="renderedMarkdown"
            />
        </div>
        <ErrorMessage :error="error" />
    </div>
</template>

<style scoped></style>
