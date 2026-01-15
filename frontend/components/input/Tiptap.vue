<template>
  <div class="tiptap-editor-wrapper">
    <div v-if="editor" class="editor-toolbar">
      <!-- History -->
      <div class="toolbar-group">
        <button
          class="toolbar-btn"
          :disabled="!editor.can().chain().focus().undo().run()"
          type="button"
          title="Undo"
          @click="editor.chain().focus().undo().run()"
        >
          <el-icon><ElIconBack /></el-icon>
        </button>
        <button
          class="toolbar-btn"
          :disabled="!editor.can().chain().focus().redo().run()"
          type="button"
          title="Redo"
          @click="editor.chain().focus().redo().run()"
        >
          <el-icon><ElIconRight /></el-icon>
        </button>
      </div>

      <div class="toolbar-divider"></div>

      <!-- Text Formatting -->
      <div class="toolbar-group">
        <button
          class="toolbar-btn"
          :class="{ 'is-active': editor.isActive('bold') }"
          type="button"
          title="Bold"
          @click="editor.chain().focus().toggleBold().run()"
        >
          <el-icon>B</el-icon>
        </button>
        <button
          class="toolbar-btn"
          :class="{ 'is-active': editor.isActive('italic') }"
          type="button"
          title="Italic"
          @click="editor.chain().focus().toggleItalic().run()"
        >
          <el-icon>I</el-icon>
        </button>
        <button
          class="toolbar-btn"
          :class="{ 'is-active': editor.isActive('strike') }"
          type="button"
          title="Strike"
          @click="editor.chain().focus().toggleStrike().run()"
        >
          <el-icon><ElIconDiscount /></el-icon>
        </button>
        <button
          class="toolbar-btn"
          :class="{ 'is-active': editor.isActive('code') }"
          type="button"
          title="Code"
          @click="editor.chain().focus().toggleCode().run()"
        >
          <el-icon><ElIconPostcard /></el-icon>
        </button>
        <button
          class="toolbar-btn"
          :class="{ 'is-active': editor.isActive({ textAlign: 'left' }) }"
          type="button"
          title="Align Left"
          @click="editor.chain().focus().setTextAlign('left').run()"
        >
          <i class="ki-textalign-left ki-outline"></i>
        </button>
        <button
          class="toolbar-btn"
          :class="{ 'is-active': editor.isActive({ textAlign: 'center' }) }"
          type="button"
          title="Align Center"
          @click="editor.chain().focus().setTextAlign('center').run()"
        >
          <i class="ki-outline ki-textalign-center"></i>
        </button>
        <button
          class="toolbar-btn"
          :class="{ 'is-active': editor.isActive({ textAlign: 'right' }) }"
          type="button"
          title="Align Right"
          @click="editor.chain().focus().setTextAlign('right').run()"
        >
          <i class="ki-textalign-right ki-outline"></i>
        </button>
        <button
          class="toolbar-btn"
          :class="{ 'is-active': editor.isActive({ textAlign: 'justify' }) }"
          type="button"
          title="Align Justify"
          @click="editor.chain().focus().setTextAlign('justify').run()"
        >
          <i class="ki-outline ki-textalign-justifycenter"></i>
        </button>
      </div>

      <div class="toolbar-divider"></div>

      <!-- Headings -->
      <div class="toolbar-group hidden sm:flex">
        <button
          class="w-8 font-bold text-xs toolbar-btn"
          :class="{ 'is-active': editor.isActive('heading', { level: 1 }) }"
          type="button"
          title="Heading 1"
          @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
        >
          H1
        </button>
        <button
          class="w-8 font-bold text-xs toolbar-btn"
          :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }"
          type="button"
          title="Heading 2"
          @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
        >
          H2
        </button>
        <button
          class="w-8 font-bold text-xs toolbar-btn"
          :class="{ 'is-active': editor.isActive('paragraph') }"
          type="button"
          title="Paragraph"
          @click="editor.chain().focus().setParagraph().run()"
        >
          P
        </button>
      </div>

      <div class="hidden sm:block toolbar-divider"></div>

      <!-- Lists -->
      <div class="toolbar-group">
        <button
          class="toolbar-btn"
          :class="{ 'is-active': editor.isActive('bulletList') }"
          type="button"
          title="Bullet List"
          @click="editor.chain().focus().toggleBulletList().run()"
        >
          <el-icon><ElIconList /></el-icon>
        </button>
        <button
          class="toolbar-btn"
          :class="{ 'is-active': editor.isActive('orderedList') }"
          type="button"
          title="Ordered List"
          @click="editor.chain().focus().toggleOrderedList().run()"
        >
          <span class="font-bold text-xs">1.</span>
        </button>
        <button
          class="toolbar-btn"
          :class="{ 'is-active': editor.isActive('blockquote') }"
          type="button"
          title="Blockquote"
          @click="editor.chain().focus().toggleBlockquote().run()"
        >
          <el-icon><ElIconChatLineRound /></el-icon>
        </button>
      </div>

      <div class="toolbar-divider"></div>

      <!-- Insert -->
      <div class="toolbar-group">
        <button
          class="toolbar-btn"
          type="button"
          title="Insert Image"
          @click="triggerImageUpload"
        >
          <el-icon><ElIconPicture /></el-icon>
        </button>
        <button
          class="toolbar-btn"
          type="button"
          title="Horizontal Rule"
          @click="editor.chain().focus().setHorizontalRule().run()"
        >
          <el-icon><ElIconMinus /></el-icon>
        </button>
      </div>
    </div>

    <!-- Upload Progress -->
    <div v-if="useMedia.loading" class="w-full h-1">
      <el-progress
        :percentage="100"
        :show-text="false"
        :stroke-width="4"
        indeterminate
        status="warning"
      />
    </div>

    <!-- Main Content -->
    <TiptapEditorContent class="editor-content" :editor="editor" />

    <!-- File Input -->
    <input
      ref="fileInputRef"
      class="hidden"
      type="file"
      accept="image/*"
      @change="handleFileChange"
    />

    <div v-if="error" class="mt-1 px-4 pb-2 text-red-500 text-xs">
      {{ error }}
    </div>
  </div>
</template>

<script setup lang="ts">
import useMedias from '@/composables/media'
import FileHandler from '@tiptap/extension-file-handler'
import TextAlign from '@tiptap/extension-text-align'
import ImageResize from 'tiptap-extension-resize-image'

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
  errors: [Object, String],
  label: String,
  name: {
    type: String,
    required: true,
  },
})

const emit = defineEmits(['update:modelValue', 'upload'])

const fileInputRef = ref<HTMLInputElement | null>(null)
const useMedia = reactive(useMedias())

async function uploadImage(file: File, id?: string) {
  const formData = new FormData()
  if (id) formData.append(id, file)
  else formData.append('file', file)

  const res = await useMedia.storeMedia(formData)

  if (res?.data) {
    emit('upload', res.data)
  }

  return res?.data.url
}

async function handleFileChange(event: Event) {
  const input = event.target as HTMLInputElement
  if (input.files && input.files[0]) {
    const file = input.files[0]
    if (editor.value) {
      const url = await uploadImage(file, 'image')

      if (url) {
        editor.value.chain().focus().setImage({ src: url }).run()
      }
    }

    input.value = ''
  }
}

function triggerImageUpload() {
  fileInputRef.value?.click()
}

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    TiptapStarterKit,
    ImageResize.configure({
      resize: {
        enabled: true,
        alwaysPreserveAspectRatio: true,
      },
    }),
    TextAlign.configure({
      types: ['heading', 'paragraph', 'image'],
    }),
    FileHandler.configure({
      allowedMimeTypes: ['image/png', 'image/jpeg', 'image/gif', 'image/webp'],
      onDrop: (currentEditor, files, pos) => {
        files.forEach(async (file) => {
          const url = await uploadImage(file, 'image')

          if (url) {
            currentEditor.chain().focus().setImage({ src: url }).run()
          }
        })
      },
      onPaste: (currentEditor, files) => {
        files.forEach(async (file) => {
          const url = await uploadImage(file, 'image')

          if (url) {
            currentEditor.chain().focus().setImage({ src: url }).run()
          }
        })
      },
    }),
  ],
  editorProps: {
    attributes: {
      class:
        'prose prose-sm sm:prose lg:prose-lg xl:prose-2xl m-4 focus:outline-none min-h-[300px]',
    },
  },
  onUpdate: ({ editor }) => {
    emit('update:modelValue', editor.getHTML())
  },
})

watch(
  () => props.modelValue,
  (value) => {
    const isSame = editor.value?.getHTML() === value
    if (isSame) return

    editor.value?.commands.setContent(value)
  },
)

onBeforeUnmount(() => {
  unref(editor)?.destroy()
})

const error = computed(() => {
  if (typeof props.errors === 'string') return null

  return props.errors?.errors?.[props.name]
    ? props.errors.errors[props.name][0]
    : null
})
</script>

<style lang="scss" scoped>
.tiptap-editor-wrapper {
  @apply flex flex-col bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-lg transition-all;

  &:focus-within {
    @apply border-primary ring-2 ring-primary-clarity;
  }
}

.editor-toolbar {
  @apply flex flex-wrap items-center gap-1 bg-gray-50 dark:bg-gray-900 p-2 border-gray-200 dark:border-gray-700 border-b rounded-t-lg;
}

.toolbar-group {
  @apply flex items-center gap-1;
}

.toolbar-divider {
  @apply bg-gray-300 dark:bg-gray-600 mx-1 w-px h-5;
}

.toolbar-btn {
  @apply flex justify-center items-center hover:bg-gray-200 dark:hover:bg-gray-700 rounded w-8 h-8 text-gray-500 hover:text-gray-900 dark:hover:text-gray-100 dark:text-gray-400 transition-colors;

  &.is-active {
    @apply bg-primary-light dark:bg-primary-light text-primary dark:text-primary;
  }

  &:disabled {
    @apply hover:bg-transparent opacity-50 hover:text-gray-500 cursor-not-allowed;
  }
}

.editor-content {
  :deep(.ProseMirror) {
    @apply p-2 outline-none min-h-[150px];

    blockquote {
      border-left: 4px solid #3d4f92;
      margin-top: 1rem;
      padding-left: 1rem;
    }

    h1 {
      font-size: 2rem !important;
      line-height: 2.25rem !important;
    }

    h2 {
      font-size: 1.5rem !important;
      line-height: 1.75rem !important;
    }

    p {
      line-height: 1.5rem !important;
      margin-top: 1rem !important;
    }

    p.is-editor-empty:first-child::before {
      content: attr(data-placeholder);
      @apply float-left h-0 text-gray-400 pointer-events-none;
    }

    img {
      @apply shadow-sm border border-gray-100 dark:border-gray-700 rounded-lg;

      &.ProseMirror-selectednode {
        @apply ring-2 ring-primary-clarity;

        margin: 1.5rem 0;
      }
    }
  }
}
</style>
