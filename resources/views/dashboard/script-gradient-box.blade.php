@push('scripts')
    <script>
        document.querySelectorAll('.gradient-box').forEach(box => {
            const current = Number(box.dataset.current);
            const total   = Number(box.dataset.total);

            // jika 0/0 → tetap merah
            if (total === 0) {
                box.style.background = `
                    linear-gradient(
                        135deg,
                        #ff7a7a,
                        #e03131
                    )
                `;
                return;
            }

            const percent = Math.min((current / total) * 100, 100);

            // jika progress 0%
            if (percent === 0) {
                box.style.background = `
                    linear-gradient(
                        135deg,
                        #ff7a7a,
                        #e03131
                    )
                `;
                return;
            }

            // jika progress 100%
            if (percent === 100) {
                box.style.background = `
                    linear-gradient(
                        135deg,
                        #51cf66,
                        #2f9e44
                    )
                `;
                return;
            }

            // jika progress 1–99%
            box.style.background = `
                linear-gradient(
                    90deg,
                    #51cf66 0%,
                    #51cf66 ${percent - 5}%,
                    #1c7ed6 ${percent + 5}%,
                    #1c7ed6 100%
                )
            `;
        });
    </script>

@endpush