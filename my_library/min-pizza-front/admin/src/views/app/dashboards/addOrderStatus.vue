<template>
  <div class="main-content">
    <breadcumb :page="'Add Order Status'" :folder="'Forms'" />
    <b-row>
      <b-col md="6">
        <b-card class="mb-30">
          <b-form>
            <div class="form-group row">
              <label for="inpuStatus" class="col-sm-12 col-form-label"
                >Status</label
              >
              <div class="col-sm-12">
                <input
                  type="text"
                  class="form-control"
                  id="inpuStatus"
                  placeholder="Order Status"
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
    title: "Form Layouts"
  }
};
</script>

