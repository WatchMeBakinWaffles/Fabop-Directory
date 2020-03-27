<script>
	import Tableau from './tableau/tableau.svelte';
	let tab = "none";

	function personnes() {
		tab = "peoples";
	}
	function institutions() {
		tab = "institutions";
	}
	function spectacles() {
		tab = "shows";
	}
	function tags() {
		tab = "tags";
	}
</script>

<h2><i class="fas fa-tachometer-alt"></i> Tableau de bord</h2>
<div class="boutons">
	<button on:click={personnes}><i class="fas fa-users"></i> Personnes </button>
	<button on:click={institutions}><i class="fas fa-university"></i> Institutions </button>
	<button on:click={spectacles}><i class="fas fa-theater-masks"></i> Spectacles </button>
	<button on:click={tags}><i class="fas fa-tags"></i> Tags </button>
</div>

{#if tab == "peoples"}
	<Tableau data="{tab}">
		<h2><i class="fas fa-users"></i>Liste des personnes</h2>
	</Tableau>
{/if}

{#if tab == "institutions"}
	<Tableau data="{tab}">
		<h2><i class="fas fa-university"></i>Liste des institutions</h2>
	</Tableau>
{/if}

{#if tab == "shows"}
	<Tableau data="{tab}">
		<h2><i class="fas fa-theater-masks"></i>Liste des spectacles</h2>
	</Tableau>
{/if}

{#if tab == "tags"}
	<Tableau data="{tab}">
		<h2><i class="fas fa-tags"></i>Liste des tags</h2>
	</Tableau>
{/if}

<h2>Logs</h2>
<p>-----</p>

<style>
.boutons{
	display: flex;
	flex-wrap: wrap;
	flex-direction: row;
}
</style>
