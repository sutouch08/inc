<style>
  h4.title {
    margin-top:10px;
  }

  .freez > th {
    top:0;
    position: sticky;
    background-color: white;
    outline: solid 1px #dddddd;
    min-height: 30px;
    height: 30px;
  }

  .tableFixHead {
    table-layout: fixed;
    min-width: 100%;
    width:1680px;
    margin-top:-1px;
    margin-left:-1px;
    margin-right:0px;
    margin-bottom: 0;
  }

  .tableFixHead thead th {
    position: sticky;
    top: -1px;
    background: #eee;
  }

  .tableFixHead tr:first-child {
    top: -1px;
  }

  .tableFixHead tr > td {
    padding: 5px !important;
  }

  .dummy{
    color: #9c27b0 !important;
  }

  .father {
    color: #3F51B5 !important;
    font-weight: bold;
  }

  .child {
    color: #03A9F4 !important;
  }

  select.input-xs {
    padding: 0px 6px;
    border-radius: 0;
  }

  tr.error {
    color:red !important;
  }

  @media (min-width: 768px) {

    .fix-no {
      left: 0;
      position: sticky;
      background-color: #eee !important;
    }

    .fix-item {
      left:45px;
      position: sticky;
    }

    .fix-header {
      z-index: 50;
      background-color: white;
      outline: solid 1px #dddddd;
    }

    td[scope=row] {
      background-color: white;
      border: 0 !important;
      outline: solid 1px #dddddd;
    }
  }
</style>
