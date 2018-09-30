<div class="content">
    {% include "layouts/slider_index.volt" %}

    <section id="donate">
        <div class="container">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Launch demo modal
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Donate now</h3>
                        </div>
                        <div class="modal-body">
                            <h4><span>1.</span> CHOOSE OR SELECT AMOUT</h4>
                            <div class="step1">
                                <div class="row">
                                    <form class="form-radio-inline">
                                        <label class="radio-inline">
                                            <input type="radio" name="optradio">$ 50
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="optradio">$ 100
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="optradio">$ 150
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="optradio">$ 200
                                        </label>
                                    </form>
                                    <div class="col-sm-12 mt-15">
                                        <input class="form-control" value="" placeholder="Insert custom value"/>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <h4 class=" mt-15"><span>2.</span> YOU ARE DONATING AS GUEST or <a href="#">Login</a></h4>
                            <div class="step1">
                                <div class="row">
                                    <div class="col-sm-6 mt-15">
                                        <input class="form-control" value="" placeholder="Name"/>
                                    </div>
                                    <div class="col-sm-6 mt-15">
                                        <input class="form-control" value="" placeholder="Surname"/>
                                    </div>
                                    <div class="col-sm-6 mt-15">
                                        <input class="form-control" value="" placeholder="Email"/>
                                    </div>
                                    <div class="col-sm-6 mt-15">
                                        <input class="form-control" value="" placeholder="Address"/>
                                    </div>
                                    <div class="col-sm-6 mt-15">
                                        <input class="form-control" value="" placeholder="City"/>
                                    </div>
                                    <div class="col-sm-6 mt-15">
                                        <input class="form-control" value="" placeholder="Country"/>
                                    </div>
                                    <div class="col-sm-12 mt-15">
                                        <textarea class="form-control" rows="4" placeholder="Message"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>