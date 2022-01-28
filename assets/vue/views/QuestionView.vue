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
          <option selected value="Selecciona una opcion">
            Selecciona una opcion
          </option>
          <option v-for="extr in extraInputSelect" :key="extr" :value="extr">
            {{ extr }}
          </option>
        </select>
      </div>

      <div class="title-question">
        <input
          type="text"
          name="title"
          class="form-control"
          v-model="title"
          placeholder="Titulo de la pregunta"
        />
      </div>
    </div>
    {{ extraInputSelect }}
    <div class="model-question">
      <p class="title">Resultado pregunta</p>
      <component :is="typeQuestion" 
      :title="title" 
       v-bind="attr"
      />
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
      typeQuestion: "",
      title: "",
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
    };
  },

  computed: {
    extraInputSelect() {
      const extras = {
        InputType: ["text", "number", "range"],
      };

      return extras[this.typeQuestion];
    },

    attr(){
      const attr = {
        type : this.extra,
        placeholder: 'Rellene la pregunta'
      };

      return attr;

    }
  },
};
</script>

<style lang="scss" scoped>
@import "/assets/admin/css/app.scss";
.question-view {
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

  .extras{
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