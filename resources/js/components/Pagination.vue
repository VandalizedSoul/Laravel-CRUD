<template>
    <div class="pagination">
      <li v-for="page in pagination.last_page" :key="page">
        <a  :class="isCurrentPage(page) ? 'active' : ''" @click.prevent="changePage(page)">{{ page }}</a>
      </li>
    </div>
</template>

<style  >
.active {
  background-color: chocolate;
}
.pagination li{
  display: inline-block;
  padding: 8px 5px;
}

.pagination li a {
  color: black;
  float: left;
  padding: 8px 16px;
  text-decoration: none;
  transition: background-color .3s;
  border: 1px solid #ddd;
}
</style>

<script>
    export default {
        props: ['pagination'],
        methods: {
            isCurrentPage(page) {
                return this.pagination.current_page === page;
            },
            changePage(page) {
                if (page > this.pagination.last_page) {
                    page = this.pagination.last_page;
                }
                //this.pagination.current_page = page;
                this.$emit('paginate',page);
            }
        },
    }
</script>