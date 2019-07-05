<template>
    <card class="px-6 py-4 resources-card">
        <h3 class="mr-3 text-base text-80 font-bold">
            <span class="uppercase">{{ resource }}</span> usage
        </h3>

        <div class="flex mb-4 resources">
            <canvas v-bind:id="resource" height="150"></canvas>
        </div>
    </card>
</template>

<script>
    import lineChart from "../chart";

    export default {
        props: ["card"],

        mounted() {
            lineChart(
                this.card.usage,
                this.resource,
                document.getElementById(this.resource)
            );
        },

        data() {
            return {
                resource: this.card.resource
            };
        }
    };
</script>
