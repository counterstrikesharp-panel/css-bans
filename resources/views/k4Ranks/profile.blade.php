<x-base-layout :scrollspy="false">

    <x-slot:pageTitle>
        {{ __('dashboard.stats') }} - CSS-BANS
    </x-slot>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <x-slot:headerFiles>
        <!--  BEGIN CUSTOM STYLE FILE  -->
        <link rel="stylesheet" href="{{asset('plugins/apex/apexcharts.css')}}">
        @vite(['resources/scss/dark/assets/components/list-group.scss'])
        @vite(['resources/scss/dark/assets/users/user-profile.scss'])
        @vite(['resources/scss/dark/assets/widgets/modules-widgets.scss'])
        <style>
            .widget-chart-two {
                min-height: 461px;
            }
            .list-unstyled{
                text-align: center;
                margin-top: 2px;
            }
            .widget-content-area [class^="cs2rating-text-"] {

                padding-left: 22px !important;
                font-style: italic;
                font-weight: normal !important;
                font-size: 18px !important;

            }
            .widget-content-area .cs2rating {
                height: 32px !important;
            }
            .progress {
                height: 22px !important;
                padding: 4px;
                width: inherit !important;
                background: #191e3a !important;
                border-radius:5px !important;
            }
            .image-container {
                position: relative;
                display: inline-block;
            }
            .w-icon {
                width: 94px !important;
                background: transparent !important;
            }
            .image-container img {
                display: block;
                border-radius: 6px;
                opacity: 1; /* Dim the image */
            }
            .centered-text {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: white; /* Text color */
                font-size: 9px; /* Adjust text size as needed */
                font-weight: bold;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7); /* Add some shadow for better readability */
            }
        </style>
        <!--  END CUSTOM STYLE FILE  -->
    </x-slot>
    <!-- END GLOBAL MANDATORY STYLES -->


    <div class="row layout-spacing ">
            <!-- MVP Widget -->
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget" style="height: 150px; background: linear-gradient(135deg, #1a2a6c, #b21f1f, #353026, #4b1248, #332e3c); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; color: white;">
                    <i class="fas fa-star" style="font-size: 40px; margin-bottom: 10px;"></i>
                    <h2 style="font-size: 40px; margin: 0;">{{ $mvp }}</h2>
                    <p style="margin-top: 5px; font-size: 16px;">{{ __('MVP') }}</p>
                </div>
            </div>

            <!-- Headshots Widget -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12 layout-spacing">
                <div class="widget" style="height: 150px; background: linear-gradient(135deg, #232526, #414345, #6b0f1a, #1f4037, #16222a); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; color: white;">
                    <i class="fas fa-crosshairs" style="font-size: 40px; margin-bottom: 10px;"></i>
                    <h2 style="font-size: 40px; margin: 0;">{{ $headshots }}</h2>
                    <p style="margin-top: 5px; font-size: 16px;">{{ __('admins.headhost') }}</p>
                </div>
            </div>

            <!-- Total Kills Widget -->
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget" style="height: 150px; background: linear-gradient(135deg, #2e003e, #3a1c71, #e94057, #a7333f, #302b63); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; color: white;">
                    <i class="fas fa-skull" style="font-size: 40px; margin-bottom: 10px;"></i>
                    <h2 style="font-size: 40px; margin: 0;">{{ $totalKills }}</h2>
                    <p style="margin-top: 5px; font-size: 16px;">{{ __('Total Kills') }}</p>
                </div>
            </div>

            <!-- Games Won Widget -->
            <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget" style="height: 150px; background: linear-gradient(135deg, #2e003e, #3a1c71, #e94057, #a7333f, #302b63); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; color: white;">
                    <i class="fas fa-trophy" style="font-size: 40px; margin-bottom: 10px;"></i>
                    <h2 style="font-size: 40px; margin: 0;">{{ $gamesWon }}</h2>
                    <p style="margin-top: 5px; font-size: 16px;">{{ __('admins.gameswon') }}</p>
                </div>
            </div>
        <!-- Content -->
        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 layout-top-spacing">
            <div class="user-profile">
                <div class="widget-content widget-content-area" style="min-height: 451px;">
                    <div class="text-center user-info">
                        <!-- Display the player's avatar -->
                        <img src="{{ $avatar }}" alt="avatar" style="width: 100px; height: 100px; border-radius: 50%;">
                        <!-- Display the player's name -->
                        <p class=""><a href="https://steamcommunity.com/profiles/{{$player->player_steamid}}/">{{ $name }}</a></p>
                    </div>

                    <div class="user-info-list">
                        <div class="">
                            <ul class="contacts-block list-unstyled" style="margin:0">
                                <li class="contacts-block__item">
                                    {!! $ratingImage !!}
                                    <!-- Display the player's rank -->

                                </li>
                                <li class="contacts-block__item">

                                    {!! $rankImage !!}
                                </li>
                                <li class="contacts-block__item">
                                    <p class="text-muted mb-0">{{ __('dashboard.lastSeen') }} <span class="badge badge-light-info rounded-pill d-inline">{{$seen}}</span></p>
                                </li>
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td><i class="fas fa-bolt me-2"></i> {{ __('First Bloods') }}</td>
                                        <td>{{ $firstBlood }}</td>
                                    </tr>

                                    <tr>
                                        <td><i class="fas fa-bomb me-2"></i> {{ __('Bombs Planted') }}</td>
                                        <td>{{ $bombPlanted }}</td>
                                    </tr>


                                    </tbody>
                                </table>

                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 layout-top-spacing">

            <div class="">

                <!-- Top Row -->
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="widget widget-chart-two">
                            <div class="widget-heading">
                                <h5 class="">{{ __('Play Time') }}</h5>
                            </div>
                            <div class="widget-content">
                                <div id="chart-1" class=""></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
                        <div class="widget widget-chart-two">
                            <div class="widget-heading">
                                <h5 class="">{{ __('Rounds') }}</h5>
                            </div>
                            <div class="widget-content">
                                <div id="chart-2" class=""></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>

    </div>

    <div class="row">
        <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
            <div class="summary layout-spacing ">
                <div class="widget-four" style="min-height: 561px;">
                    <div class="widget-heading">
                        <h5 class="">{{ __('Top Maps') }}</h5>
                    </div>
                    <div class="widget-content">
                        <div class="wepaonContent">

                                @foreach($topMaps as $map)
                                <div class="weapon-list">
                                    <div class="w-icon">
                                        <div class="image-container">
                                            <!-- Dynamically replace the map image -->
                                            <img src="{{ asset('images/maps/'.$map->map_name.'.jpg')}}" alt="{{ $map->map_name }} Image"  onerror="this.onerror=null;this.src='{{ asset('images/maps/default.jpg') }}';">
                                        </div>
                                    </div>

                                    <div class="weapon-list-details">
                                        <div class="w-weapon-info">
                                            <!-- Display the map name -->
                                            <h6>{{ $map->map_name }}</h6>
                                            <!-- Display the win rate -->
                                            <p class="weapon-count">{{ number_format($map->win_rate, 2) }}% {{ __('Win') }}</p>
                                        </div>

                                        <div class="w-weapon-stats">
                                            <div class="progress">
                                                <!-- Display the progress bar for the win rate -->
                                                <div class="progress-bar {{ ['bg-gradient-primary', 'bg-gradient-success', 'bg-gradient-warning', 'bg-gradient-danger', 'bg-gradient-info'][array_rand(['bg-gradient-primary', 'bg-gradient-success', 'bg-gradient-warning', 'bg-gradient-danger', 'bg-gradient-info'])] }}" role="progressbar"
                                                     style="width: {{ $map->win_rate }}%"
                                                     aria-valuenow="{{ number_format($map->win_rate, 2) }}" aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach




                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

            <div class="widget widget-table-three" style="min-height: 561px;">

                <div class="widget-heading">
                    <h5 class="">{{ __('Top Weapons') }}</h5>
                </div>

                <div class="widget-content">
                    <div class="table-responsive">
                        <table class="table table-scroll">
                            <thead>
                            <tr>
                                <th><div class="th-content"></div></th>
                                <th><div class="th-content th-heading">{{ __('Weapon') }}</div></th>
                                <th><div class="th-content th-heading">{{ __('dashboard.kills') }}</div></th>
                                <th><div class="th-content th-heading">{{ __('admins.headhost') }}</div></th>
                                <th><div class="th-content th-heading">{{ __('Chest') }}</div></th>
                                <th><div class="th-content th-heading">{{ __('Stomach') }}</div></th>
                            </tr>
                            </thead>
                            <tbody style="text-align: center">
                            @foreach($weaponStats as $weapon)
                                <tr>
                                    <!-- Weapon image -->
                                    <td><img src="{{ $weapon->image_url }}" alt="{{ $weapon->weapon }}" style="    width: 75px; border-radius: 10px;height: auto;background: antiquewhite;"></td>

                                    <!-- Weapon name -->
                                    <td><div class="td-content">{{ $weapon->weapon }}</div></td>

                                    <!-- Kills -->
                                    <td><div class="td-content">{{ $weapon->kills }}</div></td>

                                    <!-- Headshots -->
                                    <td><div class="td-content">{{ $weapon->headshots }}</div></td>

                                    <!-- Chest hits -->
                                    <td><div class="td-content">{{ $weapon->chest_hits }}</div></td>

                                    <!-- Stomach hits -->
                                    <td><div class="td-content">{{ $weapon->stomach_hits }}</div></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>


    </div>

    <!--  BEGIN CUSTOM SCRIPTS FILE  -->
    <x-slot:footerFiles>
        <script src="{{asset('plugins/apex/apexcharts.min.js')}}"></script>
        <script>
            var optionsTime = {
                chart: {
                    type: 'donut',
                    width: 370,
                    height: 430
                },
                colors: ['#04bbff', '#ffbc00', '#e7515a', '#e2a03f'],
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '14px',
                    markers: {
                        width: 10,
                        height: 10,
                        offsetX: -5,
                        offsetY: 0
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 30
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            background: 'transparent',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '29px',
                                    fontFamily: 'Nunito, sans-serif',
                                    color: undefined,
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '26px',
                                    fontFamily: 'Nunito, sans-serif',
                                    color: '#0e1726',
                                    offsetY: 16,
                                    formatter: function (val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: '{{ __('Total Play Time (Hours)') }}',
                                    color: '#888ea8',
                                    formatter: function (w) {
                                        return {{ $formattedPlaytime }}
                                    }
                                }
                            }
                        }
                    }
                },
                stroke: {
                    show: true,
                    width: 15,
                    colors: '#fff'
                },
                series: [{{$formattedCTPlaytime}}, {{$formattedTPlaytime}}],
                labels: ['CT', 'T'],

                responsive: [
                    {
                        breakpoint: 1440, options: {
                            chart: {
                                width: 325
                            },
                        }
                    },
                    {
                        breakpoint: 1199, options: {
                            chart: {
                                width: 380
                            },
                        }
                    },
                    {
                        breakpoint: 575, options: {
                            chart: {
                                width: 320
                            },
                        }
                    },
                ],
            };
            var optionsRounds = {
                chart: {
                    type: 'donut',
                    width: 370,
                    height: 430
                },
                colors: ['#00ff0e', '#ff0000', '#f50717', '#e2a03f'],
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '14px',
                    markers: {
                        width: 10,
                        height: 10,
                        offsetX: -5,
                        offsetY: 0
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 30
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '75%',
                            background: 'transparent',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '29px',
                                    fontFamily: 'Nunito, sans-serif',
                                    color: undefined,
                                    offsetY: -10
                                },
                                value: {
                                    show: true,
                                    fontSize: '26px',
                                    fontFamily: 'Nunito, sans-serif',
                                    color: '#0e1726',
                                    offsetY: 16,
                                    formatter: function (val) {
                                        return val
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: '{{ __('Rounds') }}',
                                    color: '#888ea8',
                                    formatter: function (w) {
                                        return {{ $roundsOverall }}
                                    }
                                }
                            }
                        }
                    }
                },
                stroke: {
                    show: true,
                    width: 15,
                    colors: '#fff'
                },
                series: [{{$roundWin}}, {{$roundLose}}],
                labels: ['{{ __('Win') }}', '{{ __('Lose') }}'],

                responsive: [
                    {
                        breakpoint: 1440, options: {
                            chart: {
                                width: 325
                            },
                        }
                    },
                    {
                        breakpoint: 1199, options: {
                            chart: {
                                width: 380
                            },
                        }
                    },
                    {
                        breakpoint: 575, options: {
                            chart: {
                                width: 320
                            },
                        }
                    },
                ],
            }

            var playerRounds = new ApexCharts(
                document.querySelector("#chart-2"),
                optionsRounds
            );

            var playTimeChart = new ApexCharts(
                document.querySelector("#chart-1"),
                optionsTime
            );

            playerRounds.render();
            playTimeChart.render();
            Apex.tooltip = {
                theme: "dark"
            }
            playerRounds.updateOptions({
                stroke: {
                    colors: '#0e1726'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                value: {
                                    color: '#bfc9d4'
                                }
                            }
                        }
                    }
                }
            })
            playTimeChart.updateOptions({
                stroke: {
                    colors: '#0e1726'
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                value: {
                                    color: '#bfc9d4'
                                }
                            }
                        }
                    }
                }
            })
        </script>
    </x-slot>
    <!--  END CUSTOM SCRIPTS FILE  -->
</x-base-layout>