<template>
  <div class="question-view">
    <div class="head">
      <div class="select-question">
        <p class="title-head">Crear pregunta</p>
        <select v-model="typeQuestion" class="form-control" name="type">
          <option selected value="Selecciona una opcion">
            Selecciona una opcion
          </option>
          <option
            v-for="type in selectQuestion"
            :key="type.id"
            :value="type.value"
          >
            {{ type.name }}
          </option>
        </select>
      </div>

      <div class="extras" v-if="extraInputSelect">
        <select v-model="extra" class="form-control" name="type">
          <option v-for="extr in extraInputSelect" :key="extr" :value="extr">
            {{ extr }}
          </option>
        </select>
      </div>
      {{ attributes }}
      <div class="typesInputs">
        <div v-for="attr in attributes" :key="attr.id">
          <p> {{attr.label}}</p>
          <input type="checkbox" v-model="attr.state" />
          <input v-bind="attr.attrs" v-model="attr.value" min="0" />
        </div>
      </div>

      <div class="title-question">
        <input
          type="text"
          name="title"
          class="form-control"
          v-model="titleQuestion"
          placeholder="Titulo de la pregunta"
        />
      </div>
    </div>

    <div class="model-question">
      <p class="title">Resultado pregunta</p>

      <component :is="typeQuestion" :title="titleQuestion" v-bind="attr" />
    </div>
  </div>
</template>

<script>
import InputType from "../components/question/InputType";
import SelectType from "../components/question/Select";

export default {
  name: "question",

  components: {
    InputType,
    SelectType,
  },

  data() {
    return {
      typeQuestion: "InputType",
      titleQuestion: "",
      extra: "",
      selectQuestion: [
        {
          id: 1,
          name: "Input",
          value: "InputType",
        },
        {
          id: 2,
          name: "Select",
          value: "SelectType",
        },
      ],

      attributes: [
        {
          id: 1,
          state: true,
          value: 0,
          name: "min",
          label: "Mínimo",
          attrs: {
            type: "number",
            min: 0,
          },
        },
        {
          id: 2,
          state: true,
          value: 100,
          name: "max",
          label: "Máximo",
          attrs: {
            type: "number",
            min: 0,
          },
        },
        {
          id: 3,
          state: false,
          value: 1,
          name: "step",
          label: "Incrementar en ",
          attrs: {
            type: "number",
            min: 1,
          },
        },
        {
          id: 4,
          state: false,
          value: "M",
          name: "units",
          label: "Unidad de medida ",
          attrs: {
            type: "text",
          },
        },
      ],
    };
  },

  computed: {
    extraInputSelect() {
      const extras = {
        InputType: ["text", "number", "range"],
      };

      return extras[this.typeQuestion];
    },

    conditionsInput() {
      const condition = {
        min: 0,
        max: 200,
      };

      return condition;
    },

    attr() {
      const attr = {
        type: this.extra,
        placeholder: "Rellene la pregunta",
        ...this.conditionsInput,
      };

      return attr;
    },
  },
};
</script>

<style lang="scss" scoped>
@import "/assets/admin/css/app.scss";
.question-view {
   input {
      border: none;
      border-bottom: solid 1px $color-app;
      border-radius: none;
      border-radius: 0;
    }
  select {
    border: none;
    border-bottom: solid 1px $color-app;
    border-radius: none;
    border-radius: 0;
  }

  .head {
    .select-question {
      margin-bottom: 2rem;
      .title-head {
        background: $color-app;
        color: #fff;
        padding: $spacing-m;
      }

      input[type="checkbox"] {
        width: 1.5rem;
        height: 1.5rem;
        &::before {
          content: "";
          background: red;
          width: 3rem;
        }
        &:checked {
          background: red;
        }
      }
    }
  }

  .typesInputs {
    display: flex;
    /* background: rgb(247 245 245); */
    flex-direction: row;
    flex-wrap: wrap;    
    justify-content: space-around;
    margin-bottom: 2rem;

    input[type="checkbox"] {
        width: 1.4rem;
        height: 1.4rem;
        &::before {
          content: "";
          background: red;
          width: 3rem;
        }
        &:checked {
          background: red;
        }
      }
  }
  .extras {
    margin-bottom: 2rem;
  }

  .title-question {
    input {
      border: none;
      border-bottom: solid 1px $color-app;
      border-radius: none;
      border-radius: 0;
    }
  }

  .model-question {
    margin-top: 6rem;

    .title {
      background: $color-app;
      color: #fff;
      padding: $spacing-m;
    }
  }
}
</style>