<section id="contact">

    <div class="container">

        <!-- section title -->
        <h2 class="section-title wow fadeInUp">Get In Touch</h2>

        <div class="spacer" data-height="60"></div>

        <div class="row">

            <div class="col-md-4">
                <!-- contact info -->
                <div class="contact-info">
                    <h3 class="wow fadeInUp">Let's talk about everything!</h3>
                    <p class="wow fadeInUp">Don't like forms? Send me an <a
                            href="mailto:name@example.com">email</a>. 👋</p>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Contact Form -->
                <form id="" class="contact-form mt-6" method="post" action="{{ route('contact.store') }}">
                    @csrf
                    <div class="messages"></div>

                    <div class="row">
                        <div class="column col-md-6">
                            <!-- Name input -->
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" id="InputName"
                                    placeholder="Your name" required="required" data-error="Name is required.">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                        <div class="column col-md-6">
                            <!-- Email input -->
                            <div class="form-group">
                                <input type="email" class="form-control" id="InputEmail" name="email"
                                    placeholder="Email address" required="required"
                                    data-error="Email is required.">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                        <div class="column col-md-12">
                            <!-- Email input -->
                            <div class="form-group">
                                <input type="text" class="form-control" id="InputSubject" name="subject"
                                    placeholder="Subject" required="required" data-error="Subject is required.">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>

                        <div class="column col-md-12">
                            <!-- Message textarea -->
                            <div class="form-group">
                                <textarea name="message" id="InputMessage" class="form-control" rows="5"
                                    placeholder="Message" required="required"
                                    data-error="Message is required."></textarea>
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" name="submit" id="" value="Submit" class="btn btn-default">Send Message</button><!-- Send Button -->

                </form>
                <!-- Contact Form end -->
            </div>

        </div>

    </div>

</section>