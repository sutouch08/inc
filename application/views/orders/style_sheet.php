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
    width:1780px;
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
    padding: 3px !important;
  }

  select.input-xs {
    padding: 0px 6px;
    border-radius: 0;
  }

  td > select.input-xs {
    border:none;
  }

  td > input.input-xs {
    border:none;
  }

  td > input.input-xs:disabled {
    background-color: white !important;
    color: #555555 !important;
  }

  .dummy > td > input.input-xs, .dummy > td > select.input-xs {
    color: #9c27b0 !important;
  }

  .father > td > input.input-xs, .father > td > select.input-xs {
    color: #3F51B5 !important;
    font-weight: bold;
  }

  .father > td > input.input-xs:disabled, .father > td > select.input-xs:disabled {
    font-style: italic;
  }

  .child > td > input.input-xs, .child > td > select.input-xs {
    color: #03A9F4 !important;
  }

  .child > td > input.input-xs:disabled, .child > td > select.input-xs:disabled {
    font-style: italic;
  }

  tr.error > td > input.input-xs, tr.error > td > select.input-xs {
    color:red !important;
  }

  @media (min-width: 768px) {

    .fix-no {
      left: 0;
      position: sticky;
      background-color: #eee !important;
    }

    .fix-add {
      left: 40px;
      position: sticky;
    }

    .fix-img {
      left:80px;
      position: sticky;
    }

    .fix-item {
      left:120px;
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
