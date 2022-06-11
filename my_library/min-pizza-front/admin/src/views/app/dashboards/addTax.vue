<template>
  <div class="main-content">
    <breadcumb :page="'Taxs'" :folder="'Taxs'" />
    <b-row>
      <b-col md="6">
        <b-card class="mb-30">
          <b-form>
            <div class="form-group row">
              <label for="name" class="col-sm-12 col-form-label"
                >Tax Name</label
              >
              <div class="col-sm-12">
                <input
                  type="text"
                  class="form-control"
                  id="name"
                  placeholder="Tax Name"
                />
              </div>
            </div>
            <div class="form-group row">
              <label for="value" class="col-sm-12 col-form-label"
                >Value</label
              >
              <div class="col-sm-12">
                <input
                  type="text"
                  class="form-control"
                  id="value"
                  placeholder="Value"
                />
              </div>
            </div>
            <div class="form-group row">
              <div class="col-sm-12">
                <button type="submit" class="btn btn-primary mt-4 btn-block">Save</button>
              </div>
            </div>
          </b-form>
        </b-card>
      </b-col>
    </b-row>
  </div>
</template>
<script>
export default {
  metaInfo: {
    // if no subcomponents specify a metaInfo.title, this title will be used
    title: "Taxs"
  }
};
</script>

