<!DOCTYPE html>
<html>

<head>
    @include('tube.commons._head')
</head>

<body>

    @include('tube.commons._header')

    <section class="container">
        <div class="col-md-12">
            <h3>18 U.S.C. 2257 Record Keeping Requirements Compliance Statement</h3>

            <p>All models on this web site are 18 years of age or older. Documentation pursuant to 18 U.S.C. 2257 Record Keeping Requirements Compliance Statement is maintained by the Custodian of Records. Some visual depictions of actual sexually explicit conduct appearing on this website were produced prior to July 3, 1995 and are exempt from the requirements of 18 U.S.C. 2257 and 28 C.F. R. 75. The date of reproduction or republication of non-exempt visual depictions of actual sexually explicit conduct is current as of the date of the visitor's entry into this website. Actual production dates for such images are contained in the records maintained pursuant to 18 U.S.C. 2257 and 28 C.F.R. 75.</p>

            <p>Date of re-issuance: Daily</p>

            <p>All models, actors, actresses and other persons that appear in any visual depiction of actual or simulated sexual conduct appearing or otherwise contained in at this website were over the age of eighteen (18) years at the time of the creation of such depictions. Some of the aforementioned depictions appearing or otherwise contained in or at this site contain only visual depictions of actual sexually explicit conduct made before July 3, 1995, and, as such, are exempt from the requirements set forth in 18 U.S.C. 2257 and C.F.R. 75.</p>
        </div>
    </section>

    @if ($language->iframe_src != "")
        <section class="container">
            @include('tube._iframe_network')
        </section>
    @endif

    @include('tube.commons._footer')
    @include('tube.commons._javascripts')
</body>
