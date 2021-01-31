<template>
  <div class="editor">
    <editor-menu-bar :editor="editor" v-slot="{ commands, isActive }">
      <div class="menubar">
        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.bold() }"
          @click="commands.bold"
        >
          <span style="font-size: 10pt">Bold</span>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.italic() }"
          @click="commands.italic"
        >
          <span style="font-size: 10pt">Italic</span>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.code() }"
          @click="commands.code"
        >
          <span style="font-size: 10pt">Code</span>
        </button>

        <button
          class="menubar__button"
          :class="{ 'is-active': isActive.todo_list() }"
          @click="commands.todo_list"
        >
          <span style="font-size: 10pt">List</span>
        </button>
      </div>
    </editor-menu-bar>
    <editor-content class="editor__content" :editor="editor" />
  </div>
</template>

<script>
import Icon from "./Icon";
import { Editor, EditorContent, EditorMenuBar } from "tiptap";
import {
  CodeBlock,
  HardBreak,
  Heading,
  TodoItem,
  TodoList,
  Bold,
  Code,
  Italic,
} from "tiptap-extensions";
export default {
  props: ["parent_content"],
  components: {
    EditorContent,
    EditorMenuBar,
    Icon,
  },
  data() {
    return {
      new_content:null,
      editor: new Editor({
        extensions: [
          new CodeBlock(),
          new HardBreak(),
          new Heading({ levels: [1, 2, 3] }),
          new TodoItem({
            nested: true,
          }),
          new TodoList(),
          new Bold(),
          new Code(),
          new Italic(),
        ],
        content: this.parent_content,
        onUpdate: ({ getHTML }) => {
          this.new_content = getHTML();
        },
      }),
    };
  },
  watch: {
    new_content(){
      //console.log("change");
      this.$emit('update',this.new_content);
    },
    parent_content(){
      this.editor.setContent(this.parent_content,false);
    }
  },
  beforeDestroy() {
    this.editor.destroy();
  }
};
</script>

<style lang="scss">
.editor {
  background-color: white;
  border: 1px;
  max-height: 300px;
  overflow:auto;
}
.menubar {
  background-color: lightgray;
}
.is-active {
  background-color: lightgreen;
}
div.ProseMirror {
  padding-left: 10px;
  font-size: 15pt;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
}
button {
  background-color:  lightgray;
  border: white;
  display: inline-block;
}
ul[data-type="todo_list"] {
  padding-left: 0;
}
li[data-type="todo_item"] {
  display: flex;
  flex-direction: row;
}
.todo-checkbox {
  border: 2px solid black;
  height: 0.9em;
  width: 0.9em;
  box-sizing: border-box;
  margin-right: 10px;
  margin-top: 0.3rem;
  user-select: none;
  -webkit-user-select: none;
  cursor: pointer;
  border-radius: 0.2em;
  background-color: transparent;
  transition: 0.4s background;
}
.todo-content {
  flex: 1;
  > p:last-of-type {
    margin-bottom: 0;
  }
  > ul[data-type="todo_list"] {
    margin: 0.5rem 0;
  }
}
li[data-done="true"] {
  > .todo-content {
    > p {
      text-decoration: line-through;
    }
  }
  > .todo-checkbox {
    background-color: black;
  }
}
li[data-done="false"] {
  text-decoration: none;
}
</style>