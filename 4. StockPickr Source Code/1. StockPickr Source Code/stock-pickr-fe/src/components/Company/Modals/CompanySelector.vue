<template>
<Modal v-if="show">
    <template v-slot:content>
        <h3 class="text-center">Choose a company to compare</h3>

        <div class="row justify-content-center pt-5">
            <div class="col-6 text-center">
                <Autocomplete
                    @input="getItems"
                    @onSelect="onSelect"
                    :results="items"
                    :display-item="displayCompany"
                    :debounce="400"
                >
                </Autocomplete>
            </div>
        </div>

        <div class="row justify-content-center text-align-center mt-4 pt-3">
            <div class="col-auto pl-1">
                <button class="btn btn-link" @click="onClose">Cancel</button>
            </div>
        </div>
    </template>
</Modal>
</template>

<script lang="ts">
import Modal from '@/components/Modal.vue';
import Autocomplete from '@/components/Autocomplete.vue';
import CompanyService from '@/services/CompanyService';
import { Ref, ref, SetupContext } from 'vue';
import { ModalProps } from '@/models/Props';
import { Company } from '@/models/Company/Company';

export default {
    name: 'CompanySelector',
    components: { Modal, Autocomplete },
    props: {
        show: {
            type: Boolean,
            required: true
        }
    },
    emits: {
        companySelected(company: Company) {
            return !!company.ticker;
        },

        close() {
            return true;
        }
    },

    setup(props: ModalProps, context: SetupContext) {
        const items: Ref<Company[]> = ref([]);
        const companyService = new CompanyService;

        const getItems = async (searcTerm: string) => {
            if (!searcTerm) {
                items.value = [];
                return;
            }

            items.value = await companyService.search<Company>(searcTerm);
        };

        const displayCompany = (company: Company) => {
            return company.fullName;
        };

        const onClose = () => {
            context.emit('close');
            items.value = [];
        };

        const onSelect = (company: Company) => {
            context.emit('companySelected', company)
            onClose();
        };

        return {
            items,
            getItems,
            displayCompany,
            onSelect,
            onClose
        };
    }
}
</script>
<style lang="scss">
    button:hover {
        text-decoration: none !important;
    }
</style>
