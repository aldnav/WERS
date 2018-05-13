<style>
    .pagination {
    padding: 20px;
    margin-bottom: 20px;
    color: #666;
    font: 20px/24px sans-serif;
}

</style>
<template>
    <nav class="pagination" v-if="!client">
        <span class="page-stats">{{pagination.from}} - {{pagination.to}} of {{pagination.total}}</span>
        <a v-if="pagination.prevPageUrl" class="button is-small pagination-previous" @click="$emit('prev');">
            <i class="fas fa-chevron-circle-left"></i>
        </a>
        <a class="button is-small pagination-previous" v-else :disabled="true">
           <i class="fas fa-chevron-circle-left"></i>
        </a>

        <a v-if="pagination.nextPageUrl" class="button is-small pagination-next" @click="$emit('next');">
            <i class="fas fa-chevron-circle-right"></i>
        </a>
        <a class="button is-small pagination-next" v-else :disabled="true">
            <i class="fas fa-chevron-circle-right"></i>
        </a>
    </nav>

    <nav class="pagination" v-else>
        <span class="page-stats">
            {{pagination.from}} - {{pagination.to}} of {{filtered.length}}
            <span v-if="filtered.length < pagination.total">(filtered from {{pagination.total}} total entries)</span>
        </span>
        <a v-if="pagination.prevPage" class="button is-small pagination-previous" @click="$emit('prev');">
            <i class="fas fa-chevron-circle-left"></i>        </a>
        <a class="button is-small pagination-previous" v-else :disabled="true">
           <i class="fas fa-chevron-circle-left"></i>
        </a>

        <a v-if="pagination.nextPage" class="button is-small pagination-next" @click="$emit('next');">
            <i class="fas fa-chevron-circle-right"></i>
        </a>
        <a class="button is-small pagination-next" v-else :disabled="true">
            <i class="fas fa-chevron-circle-right"></i>
        </a>
    </nav>
</template>

<script>
    export default {
        props: ['pagination', 'client', 'filtered']
    }
</script>