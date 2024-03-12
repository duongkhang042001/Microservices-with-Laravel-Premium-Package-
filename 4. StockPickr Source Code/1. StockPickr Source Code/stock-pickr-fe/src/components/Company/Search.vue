<template>
    <div class="input-group">
        <span class="input-icon">
            <i class="fa fa-search" aria-hidden="true"></i>
        </span>
        <input @input="keyPressed" v-model="searchTerm" type="text" placeholder="Search" class="form-control input-uppercase input-slick font-roboto">
    </div>
</template>

<script lang="ts">
import { ref, Ref, SetupContext } from 'vue';

export default {
    name: 'CompanySearch',
    emits: {
        searchTermWasChanged(searchTerm: string) {
            return searchTerm.length > 0 || searchTerm === '';
        }
    },

    setup(props: any, context: SetupContext) {
        const searchTerm: Ref<string> = ref('');

        const keyPressed = () => {
            context.emit('searchTermWasChanged', searchTerm.value);
        };

        return {
            searchTerm,
            keyPressed
        };
    }
}
</script>
