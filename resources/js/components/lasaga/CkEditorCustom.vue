<template>
    <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
</template>

<script>
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    name: 'CkeditorCustom',
    components: {
        ckeditor: CKEditor.component
    },
    props: {
        value: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            editor: ClassicEditor,
            editorData: this.value,
            editorConfig: {
                toolbar: ['heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote'],
            }
        };
    },
    watch: {
        editorData(newValue) {
            this.$emit('input', newValue);
        }
    }
};
</script>