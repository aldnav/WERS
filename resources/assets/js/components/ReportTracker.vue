<template>
    <div class="table-responsive">
        <div class="tableFilters">
            <input class="input" type="text" v-model="search" placeholder="Search Table"
                   @input="resetPagination()">

            <div class="control">
                <div class="select">
                    <select v-model="length" @change="resetPagination()">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                    </select>
                </div>
            </div>
        </div>
        <pagination :pagination="pagination" :client="true" :filtered="filteredReports"
                    @prev="--pagination.currentPage"
                    @next="++pagination.currentPage">
        </pagination>
        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
            <tbody>
                <tr v-for="report in paginated" :key="report.report_id">
                    <td>{{report.report_id}}</td>
                    <td>{{report.address}}</td>
                    <td>{{report.incident}}</td>
                    <td>{{report.unformatted_date}}</td>
                    <td>{{report.status}}</td>
                    <td>{{report.responder}}</td>
                    <td>{{report.contact_number}}</td>
                </tr>
            </tbody>
        </datatable>
        
    </div>
</template>

<script>
import Datatable from './Datatable.vue';
import Pagination from './Pagination.vue'
export default {
    components: { datatable: Datatable, pagination: Pagination },
    created() {
        this.fetchReports();
    },
    props:{
        userId:0,
        repid:0
    },
    data() {
        let sortOrders = {};

        let columns = [
            {width: '5%', label: '#', name: 'report_id', type: 'number' },
            {width: '30%', label: 'Address', name: 'address'},
            {width: '7%', label: 'Incident', name: 'incident'},
            {width: '20%', label: 'Date Reported', name: 'unformatted_date', type:'date'},
            {width: '5%', label: 'Status', name: 'status'},
            {width: '20%', label: 'Responder', name: 'responder'},
            {width: '13%', label: 'Contact', name: 'contact_number'},              
        ];

        columns.forEach((column) => {
           sortOrders[column.name] = -1;
        });
        return {
            reports: [],
            columns: columns,
            sortKey: 'unformatted_date',
            sortOrders: sortOrders,
            length: 10,
            search: '',
            tableData: {
                client: true,
            },
            pagination: {
                currentPage: 1,
                total: '',
                nextPage: '',
                prevPage: '',
                from: '',
                to: ''
            },
        }
    },
    methods: {
        fetchReports: function() {
          axios.post('/api/report-status', {repid:this.repid, userid:this.userId}).then(response=> {
              let data = response.data;
              this.reports = data.reports;
          });
        }, 
        paginate(array, length, pageNumber) {
            this.pagination.from = array.length ? ((pageNumber - 1) * length) + 1 : ' ';
            this.pagination.to = pageNumber * length > array.length ? array.length : pageNumber * length;
            this.pagination.prevPage = pageNumber > 1 ? pageNumber : '';
            this.pagination.nextPage = array.length > this.pagination.to ? pageNumber + 1 : '';
            return array.slice((pageNumber - 1) * length, pageNumber * length);
        },
        resetPagination() {
            this.pagination.currentPage = 1;
            this.pagination.prevPage = '';
            this.pagination.nextPage = '';
        },
        sortBy(key) {
            this.resetPagination();
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        },
        getIndex(array, key, value) {
            return array.findIndex(i => i[key] == value)
        },
    },
    computed: {
        filteredReports() {
            let reports = this.reports;
            if (this.search) {
                reports = reports.filter((row) => {
                    return Object.keys(row).some((key) => {
                        return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                    })
                });
            }
            let sortKey = this.sortKey;
            let order = this.sortOrders[sortKey] || 1;
            if (sortKey) {
                reports = reports.slice().sort((a, b) => {
                    let index = this.getIndex(this.columns, 'name', sortKey);
                    a = String(a[sortKey]).toLowerCase();
                    b = String(b[sortKey]).toLowerCase();
                    if (this.columns[index].type && this.columns[index].type === 'date') {
                        return (a === b ? 0 : new Date(a).getTime() > new Date(b).getTime() ? 1 : -1) * order;
                    } else if (this.columns[index].type && this.columns[index].type === 'number') {
                        return (+a === +b ? 0 : +a > +b ? 1 : -1) * order;
                    } else {
                        return (a === b ? 0 : a > b ? 1 : -1) * order;
                    }
                });
            }
            return reports;
        },
        paginated() {
            return this.paginate(this.filteredReports, this.length, this.pagination.currentPage);
        }
    }
};
</script>