<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Novedades del Sistema</h1>
            <p class="text-sm text-slate-500 mt-1">Historial de actualizaciones y mejoras</p>
        </div>
        <div class="hidden sm:block">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                v1.0.0 Oficial
            </span>
        </div>
    </div>

    <!-- Dynamic Content -->
    <!-- Dynamic Content with Enhanced Styling -->
    <div class="prose prose-slate max-w-none 
            prose-headings:font-black prose-headings:tracking-tight 
            
            /* H1: Main Title (Already handled outside, but just in case) */
            prose-h1:text-4xl prose-h1:text-slate-900 prose-h1:mb-8
            
            /* H2: Version Headers -> Styled as Badges/Cards */
            prose-h2:text-xl prose-h2:text-slate-800 prose-h2:bg-slate-50 prose-h2:border prose-h2:border-slate-200 
            prose-h2:rounded-xl prose-h2:px-6 prose-h2:py-4 prose-h2:mt-12 prose-h2:mb-6 prose-h2:flex prose-h2:items-center
            
            /* H3: Section Headers (Added, Fixed) */
            prose-h3:text-sm prose-h3:uppercase prose-h3:tracking-widest prose-h3:text-slate-500 prose-h3:mt-6 prose-h3:mb-3
            
            /* Lists */
            prose-ul:list-none prose-ul:pl-0 prose-ul:space-y-3
            prose-li:pl-6 prose-li:relative prose-li:text-slate-600 prose-li:leading-relaxed
            prose-li:before:content-['•'] prose-li:before:absolute prose-li:before:left-2 prose-li:before:text-emerald-500 prose-li:before:font-black
            
            /* Strong/Bold text */
            prose-strong:text-slate-900 prose-strong:font-bold
            
            /* Paragraphs */
            prose-p:text-slate-600 prose-p:leading-relaxed">
        
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-200/60 relative overflow-hidden">
            <!-- Decorative Timeline Line (Simulated via border on wrapper if needed, or simple clean layout) -->
             {!! $content !!}
        </div>
    </div>
</div>
