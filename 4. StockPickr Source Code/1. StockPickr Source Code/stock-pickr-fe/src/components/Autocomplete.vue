<template>
  <div class="autocomplete-container">
      <div class="input-group">
        <span class="input-icon">
            <i class="fa fa-search" aria-hidden="true"></i>
        </span>
        <input
            ref="autocompleteRef"
            type="text"
            @input="handleInput"
            v-model="searchText"
            :class="getInputClass"
            @focus="displayResults"
            @blur="hideResults"
            placeholder="Search"
            class="form-control input-uppercase input-slick">
    </div>
    <div
      :style="{ width: inputWidth + 'px' }"
      :class="getResultsContainerClass"
      v-if="shouldShowResults"
    >
      <div
        v-for="item in filteredResults"
        :key="item"
        :class="getResultsItemClass"
        @click="clickItem(item)"
        @mousedown.prevent
      >
        {{ displayItem(item) }}
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";

export default {
  name: "Autocomplete",
  props: {
    debounce: {
      type: Number,
      default: 0,
    },
    inputClass: {
      type: Array,
      default: () => [],
    },
    max: {
      type: Number,
      default: 10,
    },
    results: {
      type: Array,
      default: () => [],
    },
    resultsContainerClass: {
      type: Array,
      default: () => [],
    },
    resultsItemClass: {
      type: Array,
      default: () => [],
    },
    displayItem: {
      type: Function,
      default: (item) => {
        return typeof item === "string" ? item : item.name;
      },
    },
  },
  emits: ["input", "onSelect"],
  setup(props, context) {
    const autocompleteRef = ref();

    const inputWidth = ref(0);
    const searchText = ref("");
    let timeout;
    const showResults = ref(true);

    onMounted(() => {
      inputWidth.value = autocompleteRef.value.offsetWidth - 2;
      document.getElementsByTagName('input')[0].select();
    });

    function handleInput(e) {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        context.emit("input", e.target.value);
      }, props.debounce);
    }

    function clickItem(data) {
      context.emit("onSelect", data);
      showResults.value = false;
    }

    function displayResults() {
      showResults.value = true;
    }

    function hideResults() {
      showResults.value = false;
    }

    const getInputClass = computed(() => {
        return props.inputClass.length > 0 ? props.inputClass : ["input"];
    });

    const getResultsContainerClass = computed(() => {
      return props.resultsContainerClass.length > 0
        ? props.resultsContainerClass
        : ["results-container"];
    });

    const getResultsItemClass = computed(() => {
      return props.resultsItemClass.length > 0
        ? props.resultsItemClass
        : ["results-item"];
    });

    const shouldShowResults = computed(() => {
      return showResults.value && props.results.length > 0;
    });

    const filteredResults = computed(() => {
      return props.results.slice(0, props.max);
    });

    return {
      searchText,
      showResults,
      autocompleteRef,
      inputWidth,
      displayResults,
      hideResults,
      handleInput,
      clickItem,
      filteredResults,
      getInputClass,
      getResultsContainerClass,
      getResultsItemClass,
      shouldShowResults,
    };
  },
};
</script>

<style lang="scss" scoped>
.autocomplete-container {
  display: flex;
  flex-direction: column;

  .results-container {
    position: absolute;
    top: 38px;
    border: 1px solid black;
    z-index: 99;
    background: #272a33;
    max-width: 250px;
    width: 100%;
  }

  .results-item {
    list-style-type: none;
    padding: 12px;
    border-bottom: 1px solid black;
    color: white;
    text-align: left;

    &:hover {
      cursor: pointer;
      background-color: rgba(255, 255, 255, 0.075);
    }

    &:nth-last-child(1) {
      border-bottom: none;
    }
  }
}
</style>
