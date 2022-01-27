<template>
  <div class="question-view">
    <div class="head">
      <div class="select-question">
        <label for="type"> Seleccione el tipo de pregunta a crear</label>
        <select v-model="typeQuestion" class="form-control" name="type">
          <option
            v-for="type in selectQuestion"
            :key="type.id"
            :value="type.value"
          >
            {{ type.name }}
          </option>
        </select>
      </div>

      <div>
        <label for="title"> Titulo de la pregunta</label>
        <input type="text" name="title" class="form-control" v-model="title" />
      </div>
    </div>

    <div class="model-question">
      <p class="title">Resultado pregunta</p>
      <component 
      :is="typeQuestion"
      :title="title"
      /> 
    </div>
  </div>
</template>

<script>
import InputType from "../components/question/InputType";
import SelectType from "../components/question/Select";

export default {
  name: "question",

  data() {
    return {
      typeQuestion: "InputType",
      title:'Titulo de la pregunta',
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

  components: {
    InputType,
    SelectType,
  },
};
</script>

<style lang="scss" scoped>
@import "/assets/admin/css/app.scss";
.question-view {
  .head {   

    .select-question {
      margin-bottom: 2rem;
      label {
        font-size: 25px;
        color: $color-primary;
      }
    }
  }
  .model-question {
    .title {
      font-size: 20px;
    }
    margin-top: 1rem;
  }
}
</style>